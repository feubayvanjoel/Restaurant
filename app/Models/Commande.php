<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modèle pour la table COMMANDE
 */
class Commande extends Model
{
    protected $table = 'commande';
    protected $primaryKey = 'IDCOMMANDE';
    public $timestamps = false;

    protected $fillable = [
        'idTable',
        'idClient',
        'horaire',
        'statut',
    ];

    protected $casts = [
        'horaire' => 'datetime',
    ];

    public function table(): BelongsTo
    {
        return $this->belongsTo(GestionSalle::class, 'IDTABLE', 'IDTABLE');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'IDCLIENT', 'idClient');
    }

    public function ticket(): HasOne
    {
        return $this->hasOne(Ticket::class, 'idCommande', 'idCommande');
    }

    public function composer(): HasMany
    {
        return $this->hasMany(Composer::class, 'idCommande', 'idCommande');
    }

    public function contenir(): HasMany
    {
        return $this->hasMany(Contenir::class, 'idCommande', 'idCommande');
    }

    // Accessors for case-insensitive handling
    public function getHoraireAttribute($value)
    {
        return $this->attributes['horaire'] ?? $this->attributes['HORAIRE'] ?? $value;
    }

    public function getStatutAttribute($value)
    {
        return $this->attributes['statut'] ?? $this->attributes['STATUT'] ?? $value;
    }
}
