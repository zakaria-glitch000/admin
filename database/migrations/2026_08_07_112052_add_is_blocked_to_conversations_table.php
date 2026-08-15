<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            if (!Schema::hasColumn('conversations', 'is_blocked')) {
                $table->boolean('is_blocked')->default(false);
            }
            if (!Schema::hasColumn('conversations', 'blocked_by')) {
                $table->unsignedBigInteger('blocked_by')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            $columns = [];
            if (Schema::hasColumn('conversations', 'is_blocked')) {
                $columns[] = 'is_blocked';
            }
            if (Schema::hasColumn('conversations', 'blocked_by')) {
                $columns[] = 'blocked_by';
            }
            
            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};