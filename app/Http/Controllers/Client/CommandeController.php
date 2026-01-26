<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Commande;
use App\Models\Composer;
use App\Models\Contenir;
use App\Models\Ticket;
use App\Models\GestionSalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * Contrôleur de gestion des commandes pour les clients
 * Permet de créer, visualiser et gérer les commandes
 */
class CommandeController extends Controller
{
    /**
     * Afficher la liste des commandes du client
     */
    public function index()
    {
        $compte = auth()->user();
        $client = Client::find($compte->idProprietaire);

        $commandes = $client->commandes()
            ->where('statut', '!=', 'Supprimee')
            ->with(['ticket', 'table'])
            ->orderBy('horaire', 'desc')
            ->get();

        return view('client.commandes.index', compact('commandes'));
    }

    /**
     * Afficher le formulaire de création de commande
     */
    public function create()
    {
        // Récupérer les tables disponibles
        $tables = GestionSalle::where('statut', 'Libre')->get();

        return view('client.commandes.create', compact('tables'));
    }

    /**
     * Enregistrer une nouvelle commande dans la base de donnée
     */
    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'idTable' => ['required', 'exists:gestion_salle,IDTABLE'],
            'nb_personnes' => ['required', 'integer', 'min:1'],
            'plats' => ['nullable', 'array'],
            'plats.*.id' => ['required_with:plats', 'exists:plats,IDPLATS'],
            'plats.*.quantite' => ['required_with:plats', 'integer', 'min:1'],
            'boissons' => ['nullable', 'array'],
            'boissons.*.id' => ['required_with:boissons', 'exists:boissons,IDBOISSONS'],
            'boissons.*.quantite' => ['required_with:boissons', 'integer', 'min:1'],
        ]);

        // Validation supplémentaire : Vérifier la capacité de la table
        $table = GestionSalle::find($validated['idTable']);
        if ($table && $validated['nb_personnes'] > $table->capacite) {
            return back()
                ->withErrors(['nb_personnes' => "Le nombre de personnes ({$validated['nb_personnes']}) dépasse la capacité de la table (Table {$table->numero} : {$table->capacite} pers. max)."])
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $compte = auth()->user();
            $client = Client::find($compte->idProprietaire);

            if (!$client) {
                throw new \Exception('Client introuvable.');
            }

            // Créer la commande
            $commande = Commande::create([
                'IDTABLE' => $validated['idTable'],
                'IDCLIENT' => $client->idClient,
                'STATUT' => 'En attente',
                'HORAIRE' => now(),
                'NB_PERSONNES' => $validated['nb_personnes'],
            ]);

            $prixTotal = 0;

            // Ajouter les plats
            if (!empty($validated['plats'])) {
                foreach ($validated['plats'] as $plat) {
                    $platModel = \App\Models\Plat::find($plat['id']);

                    // Vérifier le stock disponible
                    if ($platModel->quantite < $plat['quantite']) {
                        throw new \Exception("Stock insuffisant pour le plat '{$platModel->nom}'. Disponible: {$platModel->quantite}.");
                    }

                    Composer::create([
                        'IDPLATS' => $plat['id'],
                        'IDCOMMANDE' => $commande->IDCOMMANDE,
                        'NBPLATS' => $plat['quantite'],
                    ]);

                    // Déduire du stock (utiliser la colonne en majuscules)
                    $platModel->decrement('QUANTITE', $plat['quantite']);

                    $prixTotal += $platModel->prix * $plat['quantite'];
                }
            }

            // Ajouter les boissons
            if (!empty($validated['boissons'])) {
                foreach ($validated['boissons'] as $boisson) {
                    $boissonModel = \App\Models\Boisson::find($boisson['id']);

                    // Vérifier le stock disponible
                    if ($boissonModel->quantite < $boisson['quantite']) {
                        throw new \Exception("Stock insuffisant pour la boisson '{$boissonModel->nom}'. Disponible: {$boissonModel->quantite}.");
                    }

                    Contenir::create([
                        'IDBOISSONS' => $boisson['id'],
                        'IDCOMMANDE' => $commande->IDCOMMANDE,
                        'NBBOISSONS' => $boisson['quantite'],
                    ]);

                    // Déduire du stock (utiliser la colonne en majuscules)
                    $boissonModel->decrement('QUANTITE', $boisson['quantite']);

                    $prixTotal += $boissonModel->prix * $boisson['quantite'];
                }
            }

            // Créer le ticket
            Ticket::create([
                'IDCOMMANDE' => $commande->IDCOMMANDE,
                'PRIX' => $prixTotal,
                'DATETICKET' => now(),
            ]);

            // Mettre à jour le statut de la table
            GestionSalle::where('IDTABLE', $validated['idTable'])
                ->update(['STATUT' => 'Occupee']);

            DB::commit();

            return redirect()->route('client.commandes.show', $commande)
                ->with('success', 'Votre commande a été créée avec succès !');

        } catch (\Exception $e) {
            // Log the exception for debugging
            Log::error('Erreur lors de la création d\'une commande', [
                'message' => $e->getMessage(),
                'stack' => $e->getTraceAsString(),
            ]);

            DB::rollBack();

            return back()
                ->withErrors(['error' => 'Une erreur est survenue lors de la création de votre commande.'])
                ->withInput();
        }
    }

    /**
     * Afficher les détails d'une commande
     */
    public function show(Commande $commande)
    {
        // Vérifier que la commande appartient au client connecté
        $compte = auth()->user();
        if ($commande->idClient !== $compte->idProprietaire) {
            abort(403, 'Accès non autorisé à cette commande');
        }

        $commande->load(['ticket', 'table', 'composer.plat', 'contenir.boisson']);

        return view('client.commandes.show', compact('commande'));
    }

    /**
     * Télécharger le ticket en PDF
     */
    public function downloadTicket(Commande $commande)
    {
        // Vérifier que la commande appartient au client connecté
        $compte = auth()->user();
        if ($commande->idClient !== $compte->idProprietaire) {
            abort(403, 'Accès non autorisé à cette commande');
        }

        $commande->load(['ticket', 'table', 'composer.plat', 'contenir.boisson', 'client']);

        $pdf = Pdf::loadView('pdf.ticket', compact('commande'));

        return $pdf->download('ticket-' . $commande->idCommande . '.pdf');
    }

    /**
     * Annuler une commande (soft delete via changement de statut)
     */
    public function cancel(Commande $commande)
    {
        // Vérifier que la commande appartient au client connecté
        $compte = auth()->user();
        if ($commande->idClient !== $compte->idProprietaire) {
            abort(403, 'Accès non autorisé à cette commande');
        }

        // On ne peut annuler que les commandes en attente
        if ($commande->statut !== 'En attente') {
            return back()->withErrors(['error' => 'Cette commande ne peut plus être annulée.']);
        }

        $commande->update(['STATUT' => 'Annulee']);

        return back()->with('success', 'Commande annulée avec succès.');
    }

    /**
     * Marquer une commande comme terminée (repas fini)
     */
    public function markAsCompleted(Commande $commande)
    {
        // Vérifier que la commande appartient au client connecté
        $compte = auth()->user();
        if ($commande->idClient !== $compte->idProprietaire) {
            abort(403, 'Accès non autorisé à cette commande');
        }

        // On ne peut terminer que les commandes servies
        if ($commande->statut !== 'Servie') {
            return back()->withErrors(['error' => 'Cette commande n\'a pas encore été servie.']);
        }

        $commande->update(['STATUT' => 'Terminée']);

        return back()->with('success', 'Merci de votre visite ! Commande terminée.');
    }

    /**
     * Simuler un paiement par carte (immédiat)
     */
    public function payCard(Request $request, Commande $commande)
    {
        // Basic check
        $compte = auth()->user();
        if ($commande->idClient !== $compte->idProprietaire) abort(403);

        // Validation simulée
        $request->validate(['card_number' => 'required|numeric']);

        $commande->update(['STATUT' => 'Payée']);

        // Optionnel : libérer la table automatiquement ? Le user n'a pas précisé, mais "Liberer" dans dashboard fait checkout.
        // Ici on laisse la table occupée jusqu'à ce que le serveur/admin la libère ou le client parte ?
        // Le prompt dit : "lorsque la commande est selectionnée... libérer la table...".
        // Mais pour le paiement : "si c'est par carte... statut Payée".
        // On va garder la table Occupee, car le client est peut-être encore assis.

        return redirect()->route('client.commandes.show', $commande)->with('success', 'Paiement par carte accepté ! Merci.');
    }

    /**
     * Demander un paiement en espèces (Caissier)
     */
    public function payCashRequest(Commande $commande)
    {
        $compte = auth()->user();
        if ($commande->idClient !== $compte->idProprietaire) abort(403);

        $commande->update(['STATUT' => 'En attente paiement']);

        return redirect()->route('client.commandes.show', $commande)->with('info', 'Demande de paiement en espèces envoyée. Le caissier va valider.');
    }

    /**
     * Rafraîchir la liste des commandes (API JSON)
     */
    public function refreshList()
    {
        $compte = auth()->user();
        $client = Client::find($compte->idProprietaire);

        $commandes = $client->commandes()
            ->where('statut', '!=', 'Supprimee')
            ->with(['ticket', 'table'])
            ->orderBy('horaire', 'desc')
            ->get();

        return response()->json(['commandes' => $commandes]);
    }

    /**
     * Rafraîchir les détails d'une commande (API JSON)
     */
    public function refreshDetails(Commande $commande)
    {
        $compte = auth()->user();
        if ($commande->idClient !== $compte->idProprietaire) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $commande->load(['ticket', 'table', 'composer.plat', 'contenir.boisson']);

        return response()->json(['commande' => $commande]);
    }
}
