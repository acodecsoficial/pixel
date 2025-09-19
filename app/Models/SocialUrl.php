<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialUrl extends Model
{
    use HasFactory;

    protected $table = "social_url";

    protected $fillable = [
        "instagram_url",
        "whatsapp_url",
        "telegram_url",
        "youtube_url",
        "jivo_url",
        "created_at",
        "updated_at"
    ];

}
