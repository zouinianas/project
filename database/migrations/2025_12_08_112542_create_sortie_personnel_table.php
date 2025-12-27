<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // On vérifie si la table existe déjà pour éviter les erreurs
        if (!Schema::hasTable('sortie_personnel')) {
            Schema::create('sortie_personnel', function (Blueprint $table) {
                $table->id();
                $table->foreignId('sortie_id')->constrained('sorties')->onDelete('cascade');
                $table->foreignId('personnel_id')->constrained('personnels')->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('sortie_personnel');
    }
};
