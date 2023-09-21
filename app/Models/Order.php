<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    const SHOW = [
        'user',
        'products.publication.seller_qualifications',
        'products.publication.files',
        'products.publication.categories.category',
        'products.publication.user',
        'products.publication.status',
    ];

    protected $fillable = ["user_id"];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(OrderPublication::class, 'order_id', 'id');
    }

    public static function getAllOrder($id)
    {
        return Order::with(Order::SHOW)->findOrFail($id);
    } 
}
