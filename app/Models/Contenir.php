<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modèle pour la table pivot CONTENIR (relation commande-boissons)
 */
class Contenir extends Model
{
    protected $table = 'contenir';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'idCommande',
        'idBoissons',
        'quantite',
    ];

    public function commande(): BelongsTo
    {
        return $this->belongsTo(Commande::class, 'idCommande', 'idCommande');
    }

    public function boisson(): BelongsTo
    {
        return $this->belongsTo(Boisson::class, 'idBoissons', 'idBoissons');
    }
}
