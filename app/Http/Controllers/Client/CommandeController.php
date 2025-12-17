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
     * Enregistrer une nouvelle commande
     */
    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'idTable' => ['required', 'exists:gestion_salle,idTable'],
            'plats' => ['nullable', 'array'],
            'plats.*.id' => ['required_with:plats', 'exists:plats,idPlats'],
            'plats.*.quantite' => ['required_with:plats', 'integer', 'min:1'],
            'boissons' => ['nullable', 'array'],
            'boissons.*.id' => ['required_with:boissons', 'exists:boissons,idBoissons'],
            'boissons.*.quantite' => ['required_with:boissons', 'integer', 'min:1'],
        ]);

        try {
            DB::beginTransaction();

            $compte = auth()->user();
            $client = Client::find($compte->idProprietaire);

            if (!$client) {
                throw new \Exception('Client introuvable.');
            }

            // Créer la commande
            $commande = Commande::create([
                'idTable' => $validated['idTable'],
                'idClient' => $client->idClient,
                'statut' => 'En attente',
                'horaire' => now(),
            ]);

            $prixTotal = 0;

            // Ajouter les plats
            if (!empty($validated['plats'])) {
                foreach ($validated['plats'] as $plat) {
                    $platModel = \App\Models\Plat::find($plat['id']);
                    
                    Composer::create([
                        'idPlats' => $plat['id'],
                        'idCommande' => $commande->idCommande,
                        'quantite' => $plat['quantite'],
                    ]);

                    $prixTotal += $platModel->prix * $plat['quantite'];
                }
            }

            // Ajouter les boissons
            if (!empty($validated['boissons'])) {
                foreach ($validated['boissons'] as $boisson) {
                    $boissonModel = \App\Models\Boisson::find($boisson['id']);
                    
                    Contenir::create([
                        'idBoissons' => $boisson['id'],
                        'idCommande' => $commande->idCommande,
                        'quantite' => $boisson['quantite'],
                    ]);

                    $prixTotal += $boissonModel->prix * $boisson['quantite'];
                }
            }

            // Créer le ticket
            Ticket::create([
                'idCommande' => $commande->idCommande,
                'prix' => $prixTotal,
                'dateTicket' => now(),
            ]);

            // Mettre à jour le statut de la table
            GestionSalle::where('idTable', $validated['idTable'])
                ->update(['statut' => 'Occupee']);

            DB::commit();

            return redirect()->route('client.commandes.show', $commande)
                ->with('success', 'Votre commande a été créée avec succès !');

        } catch (\Exception $e) {
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

        $commande->update(['statut' => 'Annulee']);

        return back()->with('success', 'Commande annulée avec succès.');
    }
}
