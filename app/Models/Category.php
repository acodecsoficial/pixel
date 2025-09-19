<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        "category_name",
        "category_id",
        "category_provider",
        "position",
        "is_active",
        "created_at",
        "updated_at"
    ];

    protected $appends = ['name'];

    public function games ()
    {
        return GamesApi::where('provider_name', $this->category_provider)
            ->where('status', 1)
            ->orderBy('order_value', 'ASC')
            ->get();
    }

    public function getNameAttribute ()
    {
        return $this->category_provider;
    }

}
