<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Film;
use App\Models\Genre;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::create([
            'id' => (string) Str::uuid(),
            'username' => 'user',
            'email' => 'user@example.com',
            'first_name' => 'User',
            'last_name' => 'User',
            'password' => bcrypt('user12345'),
            'balance' => 1000000,
            'auth_token' => null,
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        User::create([
            'id' => (string) Str::uuid(),
            'username' => 'admin',
            'email' => 'admin@example.com',
            'first_name' => 'Admin',
            'last_name' => 'User',
            'password' => bcrypt('admin123'),
            'balance' => 0,
            'auth_token' => null,
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $genres = Genre::factory(8)->create();
        $films = Film::factory(15)->create();
        
        $films->each(function ($film) use ($genres) {
            $film->genres()->attach(
                $genres->random(3)->pluck('id')->toArray()
            );
        });
        
        
        
    }
}