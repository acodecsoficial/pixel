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
            $table->decimal('max_risk', 8, 2)->default(2000.0)->after('affiliate_auto');
            $table->boolean('manage_risk')->default(0)->after('max_risk');
            $table->decimal('daily_max_auto_withdraw', 8, 2)->default(2000.0)->after('maxauto_withdraw');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('config', function (Blueprint $table) {
            $table->dropColumn('max_risk');
            $table->dropColumn('manage_risk');
            $table->dropColumn('daily_max_auto_withdraw');
        });
    }
};
