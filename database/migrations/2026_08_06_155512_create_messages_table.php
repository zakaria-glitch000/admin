<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained('conversations')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Chkon sift l-message
            $table->text('body')->nullable(); // Wllahou a3lam, wllat nullable hit momkin ykon messageغير fichier awla audio
            
            // Les colonnes jdids dyal l-fichiers w l-audio
            $table->string('file_path')->nullable();      // L-chemin dyal fichier f storage
            $table->string('file_type')->nullable();      // Wach 'image', 'document' wla 'audio'
            $table->string('original_name')->nullable();  // Ism l-asli dyal fichier

            $table->timestamp('read_at')->nullable();     // wach t9ra (optionnel)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};