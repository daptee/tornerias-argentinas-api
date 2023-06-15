<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerQualification extends Model
{
    use HasFactory;
    protected $table = "users_qualifications";
    
    protected $hidden = [
        'user_id',
        'seller_id'
    ];

    protected $fillable = [
        'user_id',
        'seller_id',
        'qualification',
        'comment'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function seller()
    {
        return $this->hasOne(User::class, 'id', 'seller_id');
    }

    public static function get_all_seller_qualification($id)
    {
        return SellerQualification::with(['user', 'seller'])->find($id);
    }
}
