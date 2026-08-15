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
        Schema::table('messages', function (Blueprint $table) {
            if (!Schema::hasColumn('messages', 'file_path')) {
                $table->string('file_path')->nullable()->after('body');
            }
            if (!Schema::hasColumn('messages', 'file_type')) {
                $table->string('file_type')->nullable()->after('file_path');
            }
            if (!Schema::hasColumn('messages', 'original_name')) {
                $table->string('original_name')->nullable()->after('file_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn(array_intersect(
                ['file_path', 'file_type', 'original_name'], 
                [
                    Schema::hasColumn('messages', 'file_path') ? 'file_path' : null,
                    Schema::hasColumn('messages', 'file_type') ? 'file_type' : null,
                    Schema::hasColumn('messages', 'original_name') ? 'original_name' : null,
                ]
            ));
        });
    }
};