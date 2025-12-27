<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('filieres', function (Blueprint $table) {
            // On ajoute la colonne semestre après 'niveau'
            // ex: S1, S2, S3...
            $table->string('semestre', 5)->nullable()->after('niveau')->comment('S1, S2, S3, etc.');
        });
    }

    public function down(): void
    {
        Schema::table('filieres', function (Blueprint $table) {
            $table->dropColumn('semestre');
        });
    }
};
