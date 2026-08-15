<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->foreignId('client_id')->constrained('clients');
            $table->foreignId('client_site_id')->nullable()->constrained('client_sites')->nullOnDelete();
            $table->foreignId('machine_id')->nullable()->constrained('machines')->nullOnDelete();
            $table->foreignId('ticket_category_id')->constrained('ticket_categories');
            $table->foreignId('ticket_priority_id')->constrained('ticket_priorities');
            $table->foreignId('ticket_status_id')->constrained('ticket_statuses');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->string('titre');
            $table->text('description');
            $table->enum('source', ['telephone', 'whatsapp', 'email', 'sur_place'])->default('telephone');
            $table->timestamp('date_echeance_sla')->nullable();
            $table->timestamp('date_premiere_reponse')->nullable();
            $table->timestamp('date_resolution')->nullable();
            $table->timestamp('date_cloture')->nullable();
            $table->unsignedTinyInteger('note_satisfaction')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['ticket_status_id', 'assigned_to', 'client_id']);
            $table->index(['ticket_status_id', 'ticket_priority_id']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('tickets');
    }
};