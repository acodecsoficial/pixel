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
            $table->unsignedBigInteger('sub_percent')->default(10)->after('ngr_percent');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('subPercent')->default(10)->after('referPercent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('config', function (Blueprint $table) {
            $table->dropColumn('sub_refer');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('subPercent');
        });
    }
};
