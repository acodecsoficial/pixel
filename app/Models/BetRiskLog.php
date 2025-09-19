<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BetRiskLog extends Model
{
    use HasFactory;

    protected $table = 'bet_risk_logs';

    protected $fillable = [
        'email',
        'status',
        'amount',
        'provider',
        'game'
    ];
}
