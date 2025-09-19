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
            $table->string('bg_color')->default('#212425');
            $table->string('surface_color')->default('#323637');
            $table->string('border_radius')->default('0.4rem');
            $table->string('font')->default('Inter');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('config', function (Blueprint $table) {
            $table->dropColumn('bg_color');
            $table->dropColumn('surface_color');
            $table->dropColumn('border_radius');
            $table->dropColumn('font');
        });
    }
};
