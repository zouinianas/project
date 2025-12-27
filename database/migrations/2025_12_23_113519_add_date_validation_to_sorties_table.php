<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('filieres', function (Blueprint $table) {
            // CORRECTION : On vérifie si la colonne 'semestre' existe AVANT d'essayer de la créer.
            if (!Schema::hasColumn('filieres', 'semestre')) {
                $table->string('semestre', 10)->nullable()->after('niveau')->comment('S1, S2, etc.');
            }
        });
    }

    public function down(): void
    {
        Schema::table('filieres', function (Blueprint $table) {
            // On vérifie aussi avant de supprimer pour éviter les erreurs
            if (Schema::hasColumn('filieres', 'semestre')) {
                $table->dropColumn('semestre');
            }
        });
    }
};
