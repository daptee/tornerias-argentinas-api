<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Category extends Model
{
    use HasFactory;

    public function parent_category(): HasOne
    {
        return $this->hasOne(Category::class, 'id', 'parent_category_id');
    }
}
