<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'Mohmaed Hamed Elshamy',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
            'api_token' => 'oFgQV7qYWNgTK3NNKAEfRD61O5ADglhXUhQ0uJKnkj2N1cN4UKZKBMkaX2Vr',

        ]);
    }
}
