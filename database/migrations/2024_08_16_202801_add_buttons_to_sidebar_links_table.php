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
        $cpa = DB::table('config')->value('cpa_value');

        DB::table('sidebar_links')->insert([
            [
                "group_name" => "buttons",
                "url" => "/user/refers",
                "name" => "Ganhe R$ " . number_format($cpa, 2, ',', '.') . " grátis",
                "icon" => "💥",
                "color" => null,
                "order_value" => 0
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
