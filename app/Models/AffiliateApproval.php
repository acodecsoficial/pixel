<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliateApproval extends Model
{
    use HasFactory;

    protected $table = 'affiliate_approval';

    protected $fillable = [
        'user_id',
        'user_email',
        'user_cpf',
        'user_ddi',
        'user_phone',
        'status'
    ];

}
