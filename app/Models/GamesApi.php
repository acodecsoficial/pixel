<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GamesApi extends Model
{
    use HasFactory;

    protected $table = 'games_api';

    protected $fillable = ['id', 'provider_name', 'slug', 'name', 'image', 'order_value', 'distribution', 'game_id'];

    protected $hidden = ['created_at', 'updated_at'];

    public function distributor(): BelongsTo
    {
        return $this->belongsTo(GameProvider::class, 'distribution', 'provider_name');
    }

}
