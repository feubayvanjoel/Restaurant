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
        'IDCOMMANDE',
        'IDPLATS',
        'NBPLATS',
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

    public function setIdPlatsAttribute($value)
    {
        $this->attributes['IDPLATS'] = $value;
    }

    public function getIdPlatsAttribute()
    {
        return $this->attributes['IDPLATS'] ?? null;
    }

    public function setQuantiteAttribute($value)
    {
        $this->attributes['NBPLATS'] = $value;
    }

    public function getQuantiteAttribute()
    {
        return $this->attributes['NBPLATS'] ?? null;
    }

    public function commande(): BelongsTo
    {
        return $this->belongsTo(Commande::class, 'idCommande', 'idCommande');
    }

    public function plat(): BelongsTo
    {
        return $this->belongsTo(Plat::class, 'idPlats', 'idPlats');
    }
}
