<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE webhooks CHANGE COLUMN webhook_signup register varchar(255)');
        DB::statement('ALTER TABLE webhooks CHANGE COLUMN webhook_deposit payment varchar(255)');
        DB::statement('ALTER TABLE webhooks CHANGE COLUMN webhook_deposit_paid payment_paid varchar(255)');

        Schema::table('webhooks', function (Blueprint $table) {
            $table->string('withdraw')->nullable()->default('https://')->after('payment_paid');
            $table->string('withdraw_paid')->nullable()->default('https://')->after('withdraw');
            $table->string('login')->nullable()->after('register')->default('https://');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE webhooks CHANGE COLUMN register webhook_signup varchar(255)');
        DB::statement('ALTER TABLE webhooks CHANGE COLUMN payment webhook_deposit varchar(255)');
        DB::statement('ALTER TABLE webhooks CHANGE COLUMN payment_paid webhook_deposit_paid varchar(255)');

        Schema::table('webhooks', function (Blueprint $table) {
            $table->dropColumn('withdraw');
            $table->dropColumn('withdraw_paid');
            $table->dropColumn('login');
        });
    }
};
