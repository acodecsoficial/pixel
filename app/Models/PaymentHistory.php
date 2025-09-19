<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentHistory extends Model
{
    use HasFactory;

    protected $table = 'payment_history';

    protected $fillable = [
        'type',
        'user',
        'offer_state',
        'worth',
        'bonus',
        'ggr',
        'offer_id',
        'credited',
        'provider',
        'rollover',
        'coupon'
    ];

}
