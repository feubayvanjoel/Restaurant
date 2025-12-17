<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Adresse extends Model
{
    protected $table = 'adresse';
    protected $primaryKey = 'IDADRESSE';
    public $timestamps = false;

    protected $fillable = [
        'VILLE',
        'RUE',
    ];

    public function clients()
    {
        return $this->hasMany(Client::class, 'IDADRESSE', 'IDADRESSE');
    }
}
