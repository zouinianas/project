<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourrierDepart extends Model
{
    use HasFactory;

    // Nom de la table dans la base de données
    protected $table = 'courriers_departs';

    // Les champs que l'on autorise à modifier via un formulaire
    protected $fillable = [
        'numero_ordre',    // Le compteur
        'annee',           // L'année
        'date_depart',     // Date
        'destinataire',    // Destinataire
        'objet',           // Analyse de l'affaire
        'nombre_pieces',   // Nombre de pièces jointes
        'observation',     // Remarques
        'ref_reponse',     // Pour la traçabilité future
        'user_id'          // Qui a créé l'enregistrement
    ];

    // Conversion automatique des types
    protected $casts = [
        'date_depart' => 'date', // Transforme automatiquement en objet Carbon (facile pour le formatage)
        'nombre_pieces' => 'integer',
        'numero_ordre' => 'integer',
    ];

    /**
     * Relation : Un courrier appartient à un utilisateur (celui qui l'a saisi).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
