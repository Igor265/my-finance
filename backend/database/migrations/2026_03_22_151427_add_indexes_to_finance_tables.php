<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->index('user_id');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->unique(['user_id', 'name']);
        });

        Schema::table('budgets', function (Blueprint $table) {
            $table->index('user_id');
            $table->index(['start_date', 'end_date']);
        });

        Schema::table('financial_goals', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('deadline');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->index('category_id');
        });
    }

    public function down(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'name']);
        });

        Schema::table('budgets', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['start_date', 'end_date']);
        });

        Schema::table('financial_goals', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['deadline']);
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropIndex(['category_id']);
        });
    }
};
