<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sorties', function (Blueprint $table) {
            // 1. Ajouter les colonnes pour les missions libres
            $table->string('objet')->nullable()->after('chauffeur'); // L'objet de la mission
            $table->text('personnel')->nullable()->after('objet');   // Les personnes concernées

            // 2. Rendre les colonnes "Standard" optionnelles (nullable)
            // Car une mission libre n'a PAS de module ni de destination
            $table->foreignId('module_id')->nullable()->change();
            $table->foreignId('destination_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('sorties', function (Blueprint $table) {
            // En cas d'annulation, on supprime les colonnes
            $table->dropColumn(['objet', 'personnel']);

            // Note : remettre module_id en "non-nullable" peut causer des erreurs s'il y a des données vides,
            // donc on laisse généralement comme ça dans le down().
        });
    }
};
