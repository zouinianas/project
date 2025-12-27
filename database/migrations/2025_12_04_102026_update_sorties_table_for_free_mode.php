<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sorties', function (Blueprint $table) {
            // 1. Ajouter les colonnes manquantes pour le mode libre
            if (!Schema::hasColumn('sorties', 'objectif_autre')) {
                $table->string('objectif_autre')->nullable()->after('module_id');
            }
            if (!Schema::hasColumn('sorties', 'personnels_autre')) {
                $table->string('personnels_autre')->nullable()->after('objectif_autre');
            }

            // 2. Rendre les colonnes existantes optionnelles (nullable)
            // Nécessaire car en mode libre, on n'a pas de module/destination
            $table->foreignId('module_id')->nullable()->change();
            $table->foreignId('destination_id')->nullable()->change();
            $table->string('transport')->nullable()->change();
            $table->string('chauffeur')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('sorties', function (Blueprint $table) {
            $table->dropColumn(['objectif_autre', 'personnels_autre']);
            // On ne peut pas facilement remettre les contraintes NOT NULL sans risque
        });
    }
};
