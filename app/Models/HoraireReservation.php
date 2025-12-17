<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modèle pour la table HORAIRE_RESERVATION (Réservations)
 */
class HoraireReservation extends Model
{
    protected $table = 'horaire_reservation';
    protected $primaryKey = 'IDHORAIRERESERVATION';
    public $timestamps = false;

    protected $fillable = [
        'idClient',
        'idTable',
        'date_debut',
        'date_fin',
        'nombre_personne',
        'statut',
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
        'nombre_personne' => 'integer',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'idClient', 'idClient');
    }

    public function table(): BelongsTo
    {
        return $this->belongsTo(GestionSalle::class, 'idTable', 'IDTABLE');
    }
}
