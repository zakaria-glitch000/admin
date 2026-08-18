<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_contrats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained('client_sites')->onDelete('cascade');
            $table->string('numero_contrat');
            $table->date('date_debut');
            $table->date('date_fin');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_contrats');
    }
};