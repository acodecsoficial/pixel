<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliateTransaction extends Model
{
    use HasFactory;

    protected $table = 'affiliate_transactions';

    protected $fillable = [
        'affiliate_id',
        'affiliate_email',
        'indicated_id',
        'indicated_email',
        'transaction_value',
        'affiliate_rewards',
        'affiliate_method',
        'reward_type',
    ];
}
