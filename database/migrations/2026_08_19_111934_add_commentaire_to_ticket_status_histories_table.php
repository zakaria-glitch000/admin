<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('ticket_status_histories', function (Blueprint $table) {
            if (!Schema::hasColumn('ticket_status_histories', 'commentaire')) {
                $table->text('commentaire')->nullable()->after('nouveau_status_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('ticket_status_histories', function (Blueprint $table) {
            if (Schema::hasColumn('ticket_status_histories', 'commentaire')) {
                $table->dropColumn('commentaire');
            }
        });
    }
};