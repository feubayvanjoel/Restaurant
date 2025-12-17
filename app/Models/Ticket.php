<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modèle pour la table TICKET
 */
class Ticket extends Model
{
    protected $table = 'ticket';
    protected $primaryKey = 'idTicket';
    public $timestamps = false;

    protected $fillable = [
        'idCommande',
        'dateTicket',
        'prix',
    ];

    protected $casts = [
        'dateTicket' => 'datetime',
        'prix' => 'decimal:2',
    ];

    public function commande(): BelongsTo
    {
        return $this->belongsTo(Commande::class, 'idCommande', 'idCommande');
    }
}
