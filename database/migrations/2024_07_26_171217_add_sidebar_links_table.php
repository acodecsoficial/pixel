<?php

use App\Models\SidebarLink;
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
        Schema::create('sidebar_links', function (Blueprint $table) {
            $table->id();
            $table->string('group_name')->nullable();
            $table->string('icon')->nullable();
            $table->string('name');
            $table->string('url');
            $table->boolean('new_tab')->default(false);
            $table->integer('order_value')->default(100);
            $table->string('color')->nullable();
            $table->timestamps();
        });

        // Insert initial table data
        SidebarLink::insert([
            [
                'group_name' => 'Cassino',
                'name' => 'Todos os jogos',
                'url' => '/casino/category/all',
                'icon' => 'crown',
                'order_value' => 0,
                'new_tab' => false
            ],
            [
                'group_name' => 'Cassino',
                'name' => 'Jogos Slots',
                'url' => '/casino/PG%20Soft',
                'icon' => 'deck',
                'order_value' => 1,
                'new_tab' => false
            ],
            [
                'group_name' => 'Cassino',
                'name' => 'Cassino Ao Vivo',
                'url' => '/casino/evolution',
                'icon' => 'dices',
                'order_value' => 2,
                'new_tab' => false
            ],
            [
                'group_name' => 'Cassino',
                'name' => 'Fortune Dragon',
                'url' => '/casino/pgsoft/1695365',
                'icon' => 'paw',
                'order_value' => 3,
                'new_tab' => false
            ],
            [
                'group_name' => 'Cassino',
                'name' => 'Cash Mania',
                'url' => '/casino/pgsoft/1682240',
                'icon' => 'wand',
                'order_value' => 4,
                'new_tab' => false
            ],
            [
                'group_name' => 'Cassino',
                'name' => 'Wild Ape',
                'url' => '/casino/pgsoft/1508783',
                'icon' => 'plane',
                'order_value' => 5,
                'new_tab' => false
            ],
            [
                'group_name' => 'Cassino',
                'name' => 'Fortune Tiger',
                'url' => '/casino/pgsoft/126',
                'icon' => 'paw',
                'order_value' => 6,
                'new_tab' => false
            ],
            [
                'group_name' => 'Cassino',
                'name' => 'Fortune Ox',
                'url' => '/casino/pgsoft/98',
                'icon' => 'bell',
                'order_value' => 7,
                'new_tab' => false
            ],
            [
                'group_name' => 'Cassino',
                'name' => 'Fortune Mouse',
                'url' => '/casino/pgsoft/68',
                'icon' => 'joystick',
                'order_value' => 8,
                'new_tab' => false
            ],
            [
                'group_name' => 'Cassino',
                'name' => 'Fortune Rabbit',
                'url' => '/casino/pgsoft/1543462',
                'icon' => 'fighter-plane',
                'order_value' => 9,
                'new_tab' => false
            ],
            [
                'group_name' => 'Cassino',
                'name' => 'Moto Grau',
                'url' => '/casino/hypertech/motograu',
                'icon' => 'motorcycle',
                'order_value' => 10,
                'new_tab' => false
            ],
            [
                'group_name' => 'Cassino',
                'name' => 'Lightning Roulette',
                'url' => '/casino/evolution/LightningTable01',
                'icon' => 'target',
                'order_value' => 11,
                'new_tab' => false
            ],
            [
                'group_name' => 'Cassino',
                'name' => 'Aviador',
                'url' => '/casino/hypertech/15000',
                'icon' => 'plane',
                'order_value' => 12,
                'new_tab' => false
            ],
            [
                'group_name' => 'Cassino',
                'name' => 'Wall Street Bull',
                'url' => '/casino/hypertech/wall-street',
                'icon' => 'video',
                'order_value' => 13,
                'new_tab' => false
            ],
            [
                'group_name' => 'Cassino',
                'name' => 'Baccarat',
                'url' => '/casino/evolution/oytmvb9m1zysmc44',
                'icon' => 'card',
                'order_value' => 14,
                'new_tab' => false
            ],
            [
                'group_name' => 'Cassino',
                'name' => 'Bac Bo',
                'url' => '/casino/evolution/BacBo00000000001',
                'icon' => 'dices',
                'order_value' => 15,
                'new_tab' => false
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sidebar_links');
    }
};
