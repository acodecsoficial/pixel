<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gateway extends Model
{
    use HasFactory;

    protected $table = 'gateways';

    protected $fillable = [
        "gateway_name",
        "gateway_id",
        "gateway_secret",
        "is_active",
        "show_admin",
        "position",
        "created_at",
        "updated_at"
    ];

}
