<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transport extends Model
{
    use HasFactory;

    /**
     * Les champs qui peuvent être assignés en masse.
     */
    protected $fillable = [
        'nom',
    ];

    /**
     * Obtenir les sorties associées à ce type de transport.
     */
    public function sorties()
    {
        // Un Transport (Transport) "a plusieurs" (hasMany) Sorties
        return $this->hasMany(Sortie::class);
    }
}
