<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('machines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_site_id')->constrained('client_sites');
            $table->foreignId('machine_category_id')->constrained('machine_categories');
            $table->string('marque');
            $table->string('modele');
            $table->string('numero_serie')->unique();
            $table->date('date_installation')->nullable();
            $table->date('date_fin_garantie')->nullable();
            $table->enum('statut', ['actif', 'hors_service', 'remplace'])->default('actif');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('machines');
    }
};