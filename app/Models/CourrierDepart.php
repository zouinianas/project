<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourrierDepart extends Model
{
    use HasFactory;

    // Nom exact de la table dans la base de données
    protected $table = 'courriers_departs';

    // Champs qu'on autorise à modifier (Sécurité)
    protected $fillable = [
        'numero_ordre',
        'annee',
        'date_depart',
        'destinataire',
        'objet',
        'nombre_pieces',
        'observation',
        'ref_reponse',
        'user_id'
    ];

    // Pour que Laravel traite 'date_depart' comme une vraie date (pratique pour le formatage)
    protected $casts = [
        'date_depart' => 'date',
    ];

    /**
     * Relation : Chaque courrier appartient à un créateur (User).
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
