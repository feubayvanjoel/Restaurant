<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Modèle pour la table PERSONNEL
 */
class Personnel extends Model
{
    protected $table = 'personnel';
    protected $primaryKey = 'idPersonnel';
    public $timestamps = false;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'numero',
        'adresse',
        'poste',
    ];

    public function compte(): HasOne
    {
        return $this->hasOne(Compte::class, 'idProprietaire', 'idPersonnel')->whereIn('role', ['ADMIN', 'CUISINIER', 'SERVEUR', 'CAISSIER']);
    }
}
