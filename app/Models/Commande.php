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
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'IDTABLE',
        'IDCLIENT',
        'HORAIRE',
        'STATUT',
        'NB_PERSONNES',
    ];

    protected $casts = [
        'HORAIRE' => 'datetime',
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
        return $this->hasOne(Ticket::class, 'IDCOMMANDE', 'IDCOMMANDE');
    }

    public function composer(): HasMany
    {
        return $this->hasMany(Composer::class, 'IDCOMMANDE', 'IDCOMMANDE');
    }

    public function contenir(): HasMany
    {
        return $this->hasMany(Contenir::class, 'IDCOMMANDE', 'IDCOMMANDE');
    }

    // Accessors for case-insensitive handling
    public function getIdCommandeAttribute($value)
    {
        return $this->attributes['idcommande'] ?? $this->attributes['IDCOMMANDE'] ?? $value;
    }

    public function getIdClientAttribute($value)
    {
        return $this->attributes['idclient'] ?? $this->attributes['IDCLIENT'] ?? $value;
    }

    public function getHoraireAttribute($value)
    {
        return $this->attributes['horaire'] ?? $this->attributes['HORAIRE'] ?? $value;
    }

    public function getStatutAttribute($value)
    {
        return $this->attributes['statut'] ?? $this->attributes['STATUT'] ?? $value;
    }
}
