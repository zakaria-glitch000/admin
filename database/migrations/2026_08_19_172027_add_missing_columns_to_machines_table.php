<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('machines', function (Blueprint $table) {
            if (!Schema::hasColumn('machines', 'numero_serie')) {
                $table->string('numero_serie')->nullable();
            }
            if (!Schema::hasColumn('machines', 'marque')) {
                $table->string('marque')->nullable();
            }
            if (!Schema::hasColumn('machines', 'modele')) {
                $table->string('modele')->nullable();
            }
            if (!Schema::hasColumn('machines', 'date_installation')) {
                $table->date('date_installation')->nullable();
            }
            if (!Schema::hasColumn('machines', 'date_fin_garantie')) {
                $table->date('date_fin_garantie')->nullable();
            }
            if (!Schema::hasColumn('machines', 'statut')) {
                $table->string('statut')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('machines', function (Blueprint $table) {
            $table->dropColumn(['numero_serie', 'marque', 'modele', 'date_installation', 'date_fin_garantie', 'statut']);
        });
    }
};