<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modèle pour la table pivot COMPOSER (relation commande-plats)
 */
class Composer extends Model
{
    protected $table = 'composer';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'idCommande',
        'idPlats',
        'quantite',
    ];

    public function commande(): BelongsTo
    {
        return $this->belongsTo(Commande::class, 'idCommande', 'idCommande');
    }

    public function plat(): BelongsTo
    {
        return $this->belongsTo(Plat::class, 'idPlats', 'idPlats');
    }
}
