<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // On vérifie d'abord si la colonne existe pour ne pas planter
        if (Schema::hasColumn('filieres', 'semestre')) {
            return;
        }

        Schema::table('filieres', function (Blueprint $table) {
            $table->string('semestre', 10)->nullable()->after('niveau')->comment('S1, S2, etc.');
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('filieres', 'semestre')) {
            Schema::table('filieres', function (Blueprint $table) {
                $table->dropColumn('semestre');
            });
        }
    }
};
