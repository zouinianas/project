<?php

namespace App\Models;

use App\Enums\NiveauCycle;
use App\Enums\Semestre; // <--- 1. AJOUTEZ CETTE LIGNE
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filiere extends Model
{
    use HasFactory;

    protected $table = 'filieres';

    protected $fillable = [
        'nom',
        'departement_id',
        'niveau',
        'semestre', // <--- 2. AJOUTEZ ICI
    ];

    protected $casts = [
        'niveau' => NiveauCycle::class,
        'semestre' => Semestre::class, // <--- 3. AJOUTEZ LE CAST ICI
    ];

    public function departement()
    {
        return $this->belongsTo(Departement::class);
    }

    public function modules()
    {
        return $this->hasMany(Module::class);
    }
}
