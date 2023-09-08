<?php

namespace Database\Seeders;

use App\Models\PublicationStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PublicationStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public $model = PublicationStatus::class;

    public function run()
    {
        $statuses = ["Pendiente", "En venta", "Pausada", "Cancelada", "Eliminada"];
        foreach($statuses as $status)
        {
            $this->model::firstOrCreate(['name' => $status], ['name' => $status]);
        }
    }
}
