<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $sqlFile = database_path('seeders/initial_database_structure.sql');
        $sqlCommands = file_get_contents($sqlFile);

        if (!Schema::hasTable('config')) {
            DB::unprepared($sqlCommands);
        } else {
            echo 'Banco de dados já configurado! Nenhum comando foi executado.';
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
