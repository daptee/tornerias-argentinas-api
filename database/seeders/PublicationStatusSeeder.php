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
        $statuses = ["Pendiente", "En venta", "Pausada", "Cancelada"];
        foreach($statuses as $status)
        {
            $new_publication_status = new $this->model(["name" => $status]);
            $new_publication_status->save();    
        }
    }
}
