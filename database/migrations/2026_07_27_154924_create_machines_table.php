<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('machines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_site_id')->nullable()->constrained('client_sites');
            $table->foreignId('machine_category_id')->nullable()->constrained('machine_categories');
            $table->string('marque')->nullable();
            $table->string('modele')->nullable();
            $table->string('numero_serie')->nullable()->unique();
            $table->date('date_installation')->nullable();
            $table->date('date_fin_garantie')->nullable();
            $table->string('statut')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('machines');
    }
};