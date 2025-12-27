<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('nom'); // ex: "Pétro Métamorphique"

            // MODIFIÉ : Renommé en 'filiere_id' et contraint à 'filieres'
            $table->foreignId('filiere_id')
                  ->nullable()
                  ->constrained('filieres') // <-- Changement ici
                  ->onDelete('set null');

            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('modules');
    }
};
