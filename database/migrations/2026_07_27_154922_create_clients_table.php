<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('nom_societe')->nullable();    // Champ 1: Nom de la société
            $table->string('raison_sociale')->nullable(); // Champ 2: Raison sociale
            $table->string('ice')->nullable()->unique();    // Champ 3: ICE (unique)
            $table->string('secteur_activite')->nullable();
            $table->string('telephone_principal')->nullable();
            $table->string('email')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void {
        Schema::dropIfExists('clients');
    }
};