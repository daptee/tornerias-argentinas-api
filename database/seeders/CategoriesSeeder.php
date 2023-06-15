<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public $model = Category::class;

    public function run()
    {
        $categories = ["Categoria 1", "Categoria 2", "Categoria 3"];
        foreach($categories as $category)
        {
            $this->model::firstOrCreate(['name' => $category], ['name' => $category]);
        }
    }
}
