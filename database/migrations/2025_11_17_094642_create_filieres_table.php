<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Le nom de la classe est basé sur le nom du fichier
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // On crée la table 'filieres' au lieu de 'cycles'
        Schema::create('filieres', function (Blueprint $table) {
            $table->id();
            $table->string('nom')->comment('ex: SMI, MQL, PC (Physique)');

            // On ajoute directement les colonnes des migrations que vous avez supprimées
            $table->foreignId('departement_id')
                  ->nullable()
                  ->constrained('departements') // Lié à la table 'departements'
                  ->onDelete('set null');

            $table->string('niveau')
                  ->nullable()
                  ->comment('ex: DEUG 1ère Année, Licence, Master');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('filieres');
    }
};
