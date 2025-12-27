<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('personnels', function (Blueprint $table) {
            $table->id();
            $table->string('nom'); // ex: "Ntarmouchant"
            $table->string('grade')->nullable(); // ex: "Pr"
            // On lie l'enseignant à son département
            $table->foreignId('departement_id')->nullable()->constrained('departements')->onDelete('set null');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('personnels');
    }
};
