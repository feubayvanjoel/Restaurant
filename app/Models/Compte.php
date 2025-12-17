<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modèle pour la table COMPTE
 */
class Compte extends Authenticatable
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

    protected $hidden = [
        'password',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'idProprietaire', 'idClient');
    }

    public function personnel(): BelongsTo
    {
        return $this->belongsTo(Personnel::class, 'idProprietaire', 'idPersonnel');
    }

    public function getProprietaireAttribute()
    {
        if ($this->role === 'CLIENT') {
            return $this->client;
        } else {
            return $this->personnel;
        }
    }
}
