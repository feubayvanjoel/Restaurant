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
        'IDCOMMANDE',
        'IDBOISSONS',
        'NBBOISSONS',
    ];

    // Accesseurs pour mapper les noms camelCase aux noms UPPERCASE
    public function setIdCommandeAttribute($value)
    {
        $this->attributes['IDCOMMANDE'] = $value;
    }

    public function getIdCommandeAttribute()
    {
        return $this->attributes['IDCOMMANDE'] ?? null;
    }

    public function setIdBoissonsAttribute($value)
    {
        $this->attributes['IDBOISSONS'] = $value;
    }

    public function getIdBoissonsAttribute()
    {
        return $this->attributes['IDBOISSONS'] ?? null;
    }

    public function setQuantiteAttribute($value)
    {
        $this->attributes['NBBOISSONS'] = $value;
    }

    public function getQuantiteAttribute()
    {
        return $this->attributes['NBBOISSONS'] ?? null;
    }

    public function commande(): BelongsTo
    {
        return $this->belongsTo(Commande::class, 'idCommande', 'idCommande');
    }

    public function boisson(): BelongsTo
    {
        return $this->belongsTo(Boisson::class, 'idBoissons', 'idBoissons');
    }
}
