<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sorties', function (Blueprint $table) {
            $table->id();

            // 3. Numéro d'ordre (pour 1/25, 2/25...)
            $table->integer('numero_ordre')->nullable();

            // 1. Date de Début et Date de Fin
            $table->date('date_debut');
            $table->date('date_fin')->nullable();

            // Relation avec Module (qui a tout)
            $table->foreignId('module_id')
                  ->nullable()
                  ->constrained('modules')
                  ->onDelete('set null');

            // Relation avec Destination
            $table->foreignId('destination_id')
                  ->nullable()
                  ->constrained('destinations')
                  ->onDelete('set null');

            // Transport (Enum)
            $table->string('transport')->nullable();

            // 4. Chauffeur (texte simple)
            $table->string('chauffeur')->nullable();

            $table->string('statut')->default('Confirmé');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sorties');
    }
};
