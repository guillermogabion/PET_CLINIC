<?php

use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        User::create([
            "first_name" => "admin",
            "last_name" => "admin",
            "is_active" => 1,
            "position" => "ADMIN/VET",
            "user_type" => 1,
            "email" => "admin@vet",
            "password" => "$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi"
        ]);
    }
}
