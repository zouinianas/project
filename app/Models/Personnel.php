<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personnel extends Model
{
    use HasFactory;

    protected $table = 'personnels';

    protected $fillable = [
        'nom',
        'departement_id',
    ];

    public function departement()
    {
        return $this->belongsTo(Departement::class);
    }

    // NOUVELLE RELATION CORRECTE
    public function sorties()
    {
        return $this->belongsToMany(Sortie::class, 'sortie_personnel');
    }

    // NOTE : J'ai supprimé modules() et getFilieresEnseigneesAttribute()
    // car ils dépendaient de la table 'module_personnel' qui n'existe plus.
}
