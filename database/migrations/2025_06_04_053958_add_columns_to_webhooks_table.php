<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToWebhooksTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('webhooks', function (Blueprint $table) {
            $table->string('affiliate_request')->nullable()->after('login')->default('https://');
            $table->string('affiliate_withdraw_request')->nullable()->after('affiliate_request')->default('https://');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('webhooks', function (Blueprint $table) {
            $table->dropColumn([
                'affiliate_request',
                'affiliate_withdraw_request',
            ]);
        });
    }
}
