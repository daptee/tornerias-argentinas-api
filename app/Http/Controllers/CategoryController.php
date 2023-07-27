<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function get_all_categories()
    {
        $categories = Category::with('parent_category')->get();
        return response(compact("categories"));
    }
}
