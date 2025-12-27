<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\TypeTransport;

class OrdreMission extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_ordre', 'objet', 'personnel',
        'date_debut', 'date_fin', 'transport', 'ordonne_a'
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'transport' => TypeTransport::class,
    ];
}
