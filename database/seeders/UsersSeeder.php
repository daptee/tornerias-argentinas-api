<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public $model = User::class;

    public function run()
    {
        $users_types = [
            ["name" => "Client"],
            ["name" => "Admin"]
        ];

        foreach($users_types as $user_type)
        {
            UserType::firstOrCreate(['name' => $user_type['name']], $user_type);
        }

        $users = [
            [   
                "name" => "Enzo",
                "last_name" => "Amarilla",
                "email" => "enzo100amarilla@gmail.com",
                "password" => Hash::make("12345678"),
                "user_type_id" => 1
            ],
            [   
                "name" => "Usuario",
                "last_name" => "Vendedor",
                "email" => "usuario@vendedor.com",
                "password" => Hash::make("12345678"),
                "user_type_id" => 1
            ],
            [   
                "name" => "Admin",
                "last_name" => "UserAdm",
                "email" => "usuario@admin.com",
                "password" => Hash::make("12345678"),
                "user_type_id" => 2
            ],
        ];

        foreach($users as $user)
        {
            $this->model::firstOrCreate(['email' => $user['email']], $user);
        }
    }
}
