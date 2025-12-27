<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\TypeTransport;

class Sortie extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_ordre',
        'date_debut',
        'date_fin',
        'transport',
        'chauffeur',
        'statut',           // 'Confirmé' ou 'Réservé'
        'date_validation',
        'departement_id',   // <--- AJOUT IMPORTANT
        'module_id',
        'destination_id',
        'objet',
        'personnel'
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'date_validation' => 'date',
        'transport' => TypeTransport::class,
    ];

    public function module() { return $this->belongsTo(Module::class); }
    public function destination() { return $this->belongsTo(Destination::class); }
    public function encadrants() { return $this->belongsToMany(Personnel::class, 'sortie_personnel'); }

    // Nouvelle relation directe vers Département (pour les réservations)
    public function departement() { return $this->belongsTo(Departement::class); }
}
