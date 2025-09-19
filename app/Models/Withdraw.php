<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    use HasFactory;

    protected $table = 'withdraw';

    protected $fillable = [
        'email',
        'transactionId',
        'amount',
        'amount_paid',
        'pix',
        'status',
        'date',
        'description',
        'tax_withdraw'
    ];

    public $timestamps = false;
}
