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
        Schema::table('games_api', function (Blueprint $table) {
            $table->string('game_id')->nullable()->after('slug');
        });

        DB::table('games_api')->get()->each(function ($game) {
            $gameId = last(explode('/', $game->slug));
            DB::table('games_api')
                ->where('id', $game->id)
                ->update([
                    'game_id' => $gameId
                ]);
        });

        Schema::table('games_api', function (Blueprint $table) {
            $table->string('game_id')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('games_api', function (Blueprint $table) {
            $table->dropColumn('game_id');
        });
    }
};
