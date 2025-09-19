<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameProvider extends Model
{
    use HasFactory;

    protected $table = "game_providers";

    protected $fillable = [
        "provider_name",
        "agent_code",
        "agent_token",
        "agent_secret",
        "created_at",
        "updated_at"
    ];

}
