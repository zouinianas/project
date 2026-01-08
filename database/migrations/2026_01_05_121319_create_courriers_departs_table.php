<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint; // <--- Correction ici (un seul slash)
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courriers_departs', function (Blueprint $table) {
            $table->id();

            // --- 1. L'IDENTIFICATION UNIQUE (Pour le tri) ---
            $table->integer('numero_ordre'); // Ex: 154
            $table->year('annee');           // Ex: 2026

            // --- 2. LES DONNÉES DU REGISTRE PAPIER ---
            $table->date('date_depart');     // Colonne "Date de départ"
            $table->string('destinataire');  // Colonne "Désignation du destinataire"
            $table->text('objet');           // Colonne "Analyse de l'affaire"
            $table->integer('nombre_pieces')->default(0); // Pour le Word
            $table->text('observation')->nullable();      // Colonne "Observations"

            // --- 3. TRAÇABILITÉ & LIENS ---
            $table->string('ref_reponse')->nullable(); // Colonne "Date et N° Réponse"

            // Qui a créé ce bordereau ?
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courriers_departs');
    }
};
