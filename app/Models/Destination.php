<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    use HasFactory;

    /**
     * Les champs qui peuvent être assignés en masse.
     */
    protected $fillable = [
        'nom',
    ];

    /**
     * Obtenir les sorties associées à cette destination.
     */
    public function sorties()
    {
        // Une Destination (Destination) "a plusieurs" (hasMany) Sorties
        return $this->hasMany(Sortie::class);
    }
}
