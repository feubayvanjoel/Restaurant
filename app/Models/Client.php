<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Modèle pour la table CLIENT
 */
class Client extends Model
{
    protected $table = 'client';
    protected $primaryKey = 'idClient';
    public $timestamps = false;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'numero',
        'adresse',
    ];

    public function commandes(): HasMany
    {
        return $this->hasMany(Commande::class, 'idClient', 'idClient');
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(HoraireReservation::class, 'idClient', 'idClient');
    }

    public function compte(): HasOne
    {
        return $this->hasOne(Compte::class, 'idProprietaire', 'idClient')->where('role', 'CLIENT');
    }
}
