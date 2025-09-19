<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $table = 'banners';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $appends = [ "order",];

    protected $fillable = [
        'action',
        'image',
        'id',
        'order_value',
        'type'
    ];

    public function getOrderAttribute() {
        return $this->order_value;
    }

}
