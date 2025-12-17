<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Modèle User pour l'authentification Laravel
 * Utilise la table COMPTE
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'compte';
    protected $primaryKey = 'login';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'login',
        'password',
        'idProprietaire',
        'role',
        'creer_le',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'creer_le' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Override pour utiliser login au lieu de email pour l'auth
     */
    public function getAuthIdentifierName()
    {
        return 'login';
    }

    /**
     * Relation vers Client
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'idProprietaire', 'idClient')
            ->where('role', 'CLIENT');
    }

    /**
     * Relation vers Personnel
     */
    public function personnel()
    {
        return $this->belongsTo(Personnel::class, 'idProprietaire', 'idPersonnel')
            ->whereIn('role', ['ADMIN', 'CUISINIER', 'SERVEUR', 'CAISSIER']);
    }
}
