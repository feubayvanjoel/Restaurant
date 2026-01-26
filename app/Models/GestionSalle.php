<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modèle pour la table GESTION_SALLE (Tables du restaurant)
 */
class GestionSalle extends Model
{
    protected $table = 'gestion_salle';
    protected $primaryKey = 'IDTABLE';
    public $timestamps = false;
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'NUMERO',
        'CAPACITE',
        'STATUT',
    ];

    public function commandes(): HasMany
    {
        return $this->hasMany(Commande::class, 'IDTABLE', 'IDTABLE');
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(HoraireReservation::class, 'IDTABLE', 'IDTABLE');
    }

    /**
     * Vérifie si la table est libre
     */
    public function isLibre(): bool
    {
        return $this->statut === 'Libre';
    }

    /**
     * Vérifie si la table est occupée
     */
    public function isOccupee(): bool
    {
        return $this->statut === 'Occupee';
    }

    /**
     * Vérifie si la table est réservée
     */
    public function isReservee(): bool
    {
        return $this->statut === 'Reservee';
    }

    // Accessors for case-insensitive handling
    public function getIdTableAttribute($value)
    {
        return $this->attributes['idTable'] ?? $this->attributes['IDTABLE'] ?? $value;
    }

    public function getNumeroAttribute($value)
    {
        return $this->attributes['numero'] ?? $this->attributes['NUMERO'] ?? $value;
    }

    public function getStatutAttribute($value)
    {
        return $this->attributes['statut'] ?? $this->attributes['STATUT'] ?? $value;
    }

    public function getCapaciteAttribute($value)
    {
        return $this->attributes['capacite'] ?? $this->attributes['CAPACITE'] ?? $this->attributes['NBPLACE'] ?? $this->attributes['nbplace'] ?? $value;
    }

    /**
     * Nombre de personnes occupant actuellement la table
     * Basé sur les commandes en cours (ni Terminée, ni Annulée)
     */
    public function getOccupantsAttribute()
    {
        return $this->commandes()
            ->whereNotIn('statut', ['Terminée', 'Annulee'])
            ->sum('NB_PERSONNES') ?? 0;
    }

    /**
     * Nombre de places restantes
     */
    public function getPlacesRestantesAttribute()
    {
        return max(0, $this->capacite - $this->occupants);
    }
    /**
     * Obtenir le client actuel (Nom + ID) pour les tables occupées/réservées
     */
    public function getCurrentClientAttribute()
    {
        // Priorité aux commandes en cours
        $commande = $this->commandes()
            ->whereIn('STATUT', ['Confirmee', 'En préparation', 'Préparée', 'Servie', 'En attente'])
            ->latest('HORAIRE')
            ->first();
            
        if ($commande && $commande->client) {
            return $commande->client->prenom . ' ' . $commande->client->nom . ' (#' . $commande->client->idClient . ')';
        }

        // Sinon voir réservations actives
        $reservation = $this->reservations()
            ->where('statut', 'ACTIVE')
            ->orderBy('date_debut')
            ->first();

        if ($reservation && $reservation->client) {
            return $reservation->client->prenom . ' ' . $reservation->client->nom . ' (#' . $reservation->client->idClient . ')';
        }

        return null;
    }

    /**
     * Date/Heure d'expiration de la commande (Début + 2h30)
     * Retourne timestamp ou null
     */
    public function getOrderExpiryAttribute()
    {
        $commande = $this->commandes()
            ->whereIn('STATUT', ['Confirmee', 'En préparation', 'Préparée', 'Servie', 'En attente'])
            ->latest('HORAIRE')
            ->first();
            
        if ($commande) {
            // Ajouter 2h30 (150 minutes) à l'heure de commande
            return \Carbon\Carbon::parse($commande->horaire)->addMinutes(150)->timestamp * 1000; // MS pour JS
        }
        
        return null;
    }
}
