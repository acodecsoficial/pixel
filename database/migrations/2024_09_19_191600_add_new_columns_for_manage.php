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
        Schema::table('config', function (Blueprint $table) {
            $table->decimal('w_min_affiliate', 10, 2)->default(0);
            $table->decimal('w_max_affiliate', 10, 2)->default(0);
            $table->decimal('dl_affiliate', 10, 2)->default(0);
        });

        Schema::table('games_history', function (Blueprint $table) {
            $table->boolean('bonus')->default(0)->after('demo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('config', function (Blueprint $table) {
            $table->dropColumn('w_min_affiliate');
            $table->dropColumn('w_max_affiliate');
            $table->dropColumn('dl_affiliate');
        });

        Schema::table('games_history', function (Blueprint $table) {
            $table->dropColumn('bonus');
        });
    }
};
