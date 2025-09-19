<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GameProvider;

class FiversProviderSeeder extends Seeder
{
    public function run(): void
    {
        GameProvider::updateOrCreate(
            ['provider_name' => 'FIVERS'],
            [
                'agent_code'  => 'santobet',
                'agent_token' => env('PLAYFIVERS_AGENT_TOKEN', ''),
                'agent_secret'=> env('PLAYFIVERS_SECRET_KEY', ''),
            ]
        );
    }
}
