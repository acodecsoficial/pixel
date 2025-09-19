<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletChange extends Model
{
    use HasFactory;

    protected $table = 'wallet_change';
	public $timestamps = false;

	protected $fillable = [
        'id',
        'user',
        'change',
        'reason',
        'game',
        'value_entry',
        'value_roi',
        'value_total',
        'value_bonus',
        'transaction_date'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

}
