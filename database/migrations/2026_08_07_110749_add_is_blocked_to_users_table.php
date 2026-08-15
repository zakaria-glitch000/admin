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
        // 1. نحيدو العمود القديم من جدول users باش ميبقاش عامل مشكل global
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'is_blocked')) {
                $table->dropColumn('is_blocked');
            }
        });

        // 2. نضيفو أعمدة البلۆك الخاصة بكل محادثة في جدول conversations
        Schema::table('conversations', function (Blueprint $table) {
            if (!Schema::hasColumn('conversations', 'is_blocked')) {
                $table->boolean('is_blocked')->default(false);
            }
            if (!Schema::hasColumn('conversations', 'blocked_by')) {
                $table->unsignedBigInteger('blocked_by')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->dropColumn(['is_blocked', 'blocked_by']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_blocked')->default(false);
        });
    }
};