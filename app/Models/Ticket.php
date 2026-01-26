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
    protected $primaryKey = 'IDTICKET';
    public $timestamps = false;
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'IDCOMMANDE',
        'DATETICKET',
        'PRIX',
    ];

    protected $casts = [
        'DATETICKET' => 'datetime',
        'PRIX' => 'decimal:2',
    ];

    public function commande(): BelongsTo
    {
        return $this->belongsTo(Commande::class, 'IDCOMMANDE', 'IDCOMMANDE');
    }

    // Accessors for case-insensitive handling
    public function getIdTicketAttribute($value)
    {
        return $this->attributes['idticket'] ?? $this->attributes['IDTICKET'] ?? $value;
    }

    public function getPrixAttribute($value)
    {
        return $this->attributes['prix'] ?? $this->attributes['PRIX'] ?? $value;
    }

    public function getDateTicketAttribute($value)
    {
        return $this->attributes['dateticket'] ?? $this->attributes['DATETICKET'] ?? $value;
    }
}
