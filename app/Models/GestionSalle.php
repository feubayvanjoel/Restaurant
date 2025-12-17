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

    protected $fillable = [
        'numero',
        'capacite',
        'statut',
    ];

    public function commandes(): HasMany
    {
        return $this->hasMany(Commande::class, 'idTable', 'idTable');
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(HoraireReservation::class, 'idTable', 'idTable');
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
        return $this->attributes['capacite'] ?? $this->attributes['CAPACITE'] ?? $value;
    }
}
