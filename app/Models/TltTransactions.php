<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TltTransactions extends Model
{
    use HasFactory;

    protected $table = 'tlt_transactions';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'transaction_type',
        'reason',
        'amount',
        'currency_code',
        'transaction_timestamp',
        'transaction_id',
        'transaction_status',
        'round_id',
        'round_finished',
        'game_id',
        'user_id',
        'token',
        'context',
    ];

    protected $casts = [
        'transaction_timestamp' => 'datetime',
        'context' => 'array',
    ];
}
