<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('client_sites', function (Blueprint $table) {
            $table->string('numero_contrat')->nullable()->after('ville');
            $table->date('date_debut_contrat')->nullable()->after('numero_contrat');
            $table->date('date_fin_contrat')->nullable()->after('date_debut_contrat');
        });
    }

    public function down(): void {
        Schema::table('client_sites', function (Blueprint $table) {
            $table->dropColumn(['numero_contrat', 'date_debut_contrat', 'date_fin_contrat']);
        });
    }
};