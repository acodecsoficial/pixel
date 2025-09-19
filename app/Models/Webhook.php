<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Webhook extends Model
{
    use HasFactory;

    protected $fillable = [
        "webhook_signup",
        "webhook_deposit",
        "webhook_deposit_paid",
        "webhook_signup",
        "created_at",
        "updated_at"
    ];

}
