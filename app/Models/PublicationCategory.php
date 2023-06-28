<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicationCategory extends Model
{
    use HasFactory;
    
    protected $table = "publications_categories";
    
    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    protected $hidden = [
        // 'category_id'
    ];
}
