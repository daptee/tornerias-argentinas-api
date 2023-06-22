<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OrderPublication extends Model
{
    use HasFactory;

    const SHOW = [
        'publication'
    ];

    protected $table = "orders_publications";
    
    protected $fillable = [
        "order_id",
        "publication_id",
        "quantity",
        "unit_price"
    ];

    public function order(): HasOne
    {
        return $this->hasOne(Order::class, 'id', 'order_id');
    }

    public function publication(): HasOne
    {
        return $this->hasOne(Publication::class, 'id', 'publication_id');
    }

    public static function getAllOrderPublication($id)
    {
        return OrderPublication::with(OrderPublication::SHOW)->findOrFail($id);
    } 
}
