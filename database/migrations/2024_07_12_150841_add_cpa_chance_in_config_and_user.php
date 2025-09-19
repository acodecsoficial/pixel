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
            $table->integer('cpa_chance')->default(100)->after('cpa_value');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->integer('cpa_chance')->default(100)->after('value_cpa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
