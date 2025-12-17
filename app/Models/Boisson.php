<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modèle pour la table BOISSONS
 */
class Boisson extends Model
{
    protected $table = 'boissons';
    protected $primaryKey = 'IDBOISSONS';
    public $timestamps = false;
    
    protected $fillable = [
        'nom',
        'prix',
        'quantite',
    ];

    protected $casts = [
        'PRIX' => 'decimal:2',
        'QUANTITE' => 'integer',
        'prix' => 'decimal:2',
    ];

    // --- Accessors (Lecture) ---
    public function getNomAttribute($value)
    {
        return $this->attributes['nom'] ?? $this->attributes['NOM'] ?? $value;
    }

    public function getPrixAttribute($value)
    {
        return $this->attributes['prix'] ?? $this->attributes['PRIX'] ?? $value;
    }

    public function getQuantiteAttribute($value)
    {
        return $this->attributes['quantite'] ?? $this->attributes['QUANTITE'] ?? $value;
    }

    // --- Mutators (Écriture) ---
    public function setNomAttribute($value)
    {
        $this->attributes['NOM'] = $value;
        $this->attributes['nom'] = $value;
    }

    public function setPrixAttribute($value)
    {
        $this->attributes['PRIX'] = $value;
        $this->attributes['prix'] = $value;
    }

    public function setQuantiteAttribute($value)
    {
        $this->attributes['QUANTITE'] = $value;
        $this->attributes['quantite'] = $value;
    }
}
