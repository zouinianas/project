<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departement extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'couleur',
    ];

    // العلاقات
    public function sorties()
    {
        return $this->hasMany(Sortie::class);
    }

    public function filieres()
    {
        return $this->hasMany(Filiere::class);
    }

    public function personnels()
    {
        return $this->hasMany(Personnel::class);
    }
}
