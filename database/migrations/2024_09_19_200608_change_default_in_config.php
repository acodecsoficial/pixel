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
            $table->dropColumn('w_min_affiliate');
            $table->dropColumn('w_max_affiliate');
            $table->dropColumn('dl_affiliate');
        });

        Schema::table('config', function (Blueprint $table) {
            $table->decimal('w_min_affiliate', 10, 2)->default(500);
            $table->decimal('w_max_affiliate', 10, 2)->default(1000);
            $table->integer('dl_affiliate')->default(5);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {}
};
