<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ordre_missions', function (Blueprint $table) {
            $table->id();
            // Le numéro d'ordre qui sera partagé logiquement avec la table sorties
            $table->integer('numero_ordre')->unique();

            $table->string('objet'); // Ex: Réunion, Stage...
            $table->text('personnel')->nullable(); // Ex: Mr Alami

            $table->date('date_debut');
            $table->date('date_fin')->nullable();
            $table->string('transport')->nullable();
            $table->string('ordonne_a')->nullable(); // Nom du chauffeur ou personne concernée

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ordre_missions');
    }
};
