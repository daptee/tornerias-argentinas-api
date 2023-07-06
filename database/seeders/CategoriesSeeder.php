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
        $categories = [
            ["id" => 3, "name" => "INSUMOS TORNO", "parent_category_id" => null],
            ["id" => 4, "name" => "MAQUINARIAS", "parent_category_id" => null],
            ["id" => 5, "name" => "INSUMOS FRESADORAS", "parent_category_id" => null],
            ["id" => 6, "name" => "INSTRUMENTOS DE MEDICIÓN", "parent_category_id" => null],
            ["id" => 7, "name" => "HERRAMIENTAS", "parent_category_id" => null],
            ["id" => 8, "name" => "PORTAHERRAMIENTAS", "parent_category_id" => 3],
            ["id" => 9, "name" => "INSERTOS", "parent_category_id" => 3],
            ["id" => 10, "name" => "CONTRA PUNTA GIRATORIA", "parent_category_id" => 3],
            ["id" => 11, "name" => "CONOS DE REDUCCIÓN", "parent_category_id" => 3],
            ["id" => 12, "name" => "MANDRILES", "parent_category_id" => 3],
            ["id" => 13, "name" => "MECHAS", "parent_category_id" => 3],
            ["id" => 14, "name" => "AMOLADORA DE BANCO", "parent_category_id" => 3],
            ["id" => 15, "name" => "OTROS", "parent_category_id" => 3],
            ["id" => 16, "name" => "TORNOS", "parent_category_id" => 4],
            ["id" => 17, "name" => "FRESAS", "parent_category_id" => 4],
            ["id" => 18, "name" => "BALANCINES", "parent_category_id" => 4],
            ["id" => 19, "name" => "SERRUCHOS MECÁNICOS", "parent_category_id" => 4],
            ["id" => 20, "name" => "SIERRA SIN FIN", "parent_category_id" => 4],
            ["id" => 21, "name" => "GUILLOTINAS", "parent_category_id" => 4],
            ["id" => 22, "name" => "PLEGADORAS", "parent_category_id" => 4],
            ["id" => 23, "name" => "PRENSAS", "parent_category_id" => 4],
            ["id" => 24, "name" => "AGUJEREADORAS", "parent_category_id" => 4],
            ["id" => 25, "name" => "RECTIFICADORAS", "parent_category_id" => 4],
            ["id" => 26, "name" => "LIMADORAS", "parent_category_id" => 4],
            ["id" => 27, "name" => "COMPRESORES", "parent_category_id" => 4],
            ["id" => 28, "name" => "SOLDADORAS", "parent_category_id" => 4],
            ["id" => 29, "name" => "OTROS", "parent_category_id" => 4],
            ["id" => 30, "name" => "FRESAS", "parent_category_id" => 5],
            ["id" => 31, "name" => "FRESAS PARA INSERTOS", "parent_category_id" => 5],
            ["id" => 32, "name" => "DIVISORES", "parent_category_id" => 5],
            ["id" => 33, "name" => "PARALELAS", "parent_category_id" => 5],
            ["id" => 34, "name" => "CABEZAL ALESADOR", "parent_category_id" => 5],
            ["id" => 35, "name" => "PINZAS", "parent_category_id" => 5],
            ["id" => 36, "name" => "CONOS PORTA FRESAS", "parent_category_id" => 5],
            ["id" => 37, "name" => "MECHAS", "parent_category_id" => 5],
            ["id" => 38, "name" => "MACHOS", "parent_category_id" => 5],
            ["id" => 39, "name" => "MORZAS", "parent_category_id" => 5],
            ["id" => 40, "name" => "CLAMP DE SUJECIÓN", "parent_category_id" => 5],
            ["id" => 41, "name" => "MÓDULOS", "parent_category_id" => 5],
            ["id" => 42, "name" => "CALIBRES", "parent_category_id" => 6],
            ["id" => 43, "name" => "MICROMETROS", "parent_category_id" => 6],
            ["id" => 44, "name" => "ALESOMETROS", "parent_category_id" => 6],
            ["id" => 45, "name" => "GONIOMETROS", "parent_category_id" => 6],
            ["id" => 46, "name" => "TELESCOPINES", "parent_category_id" => 6],
            ["id" => 47, "name" => "ESCUADRAS", "parent_category_id" => 6],
            ["id" => 48, "name" => "COMPARADORES", "parent_category_id" => 6],
            ["id" => 49, "name" => "PALPADORES", "parent_category_id" => 6],
            ["id" => 50, "name" => "COMPAS", "parent_category_id" => 6],
            ["id" => 51, "name" => "BASE MAGNÉTICA", "parent_category_id" => 6],
            ["id" => 52, "name" => "OTROS", "parent_category_id" => 6],
            ["id" => 53, "name" => "AMOLADORAS", "parent_category_id" => 7],
            ["id" => 54, "name" => "TALADROS DE MANO", "parent_category_id" => 7],
            ["id" => 55, "name" => "LIMAS", "parent_category_id" => 7],
            ["id" => 56, "name" => "LLAVES DE MANOS", "parent_category_id" => 7],
            ["id" => 57, "name" => "PIEDRAS ESMERIL", "parent_category_id" => 7],
            ["id" => 58, "name" => "CALISULES", "parent_category_id" => 7],
            ["id" => 59, "name" => "OTROS", "parent_category_id" => 7]
        ];
    
        foreach ($categories as $category) {
            $this->model::firstOrCreate($category, $category);
        }
    }
    
}
