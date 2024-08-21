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

        // DB::statement('SET FOREIGN_KEY_CHECKS=0;');


        // DB::table('film_genre')->truncate();
        // DB::table('films')->truncate();
        // DB::table('genres')->truncate();


        // DB::statement('SET FOREIGN_KEY_CHECKS=1;');


        // $genres = Genre::factory(2)->create();


        // Film::factory(10)
        //     ->hasAttached($genres->random(2))
        //     ->create();

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
        // User::factory(10)->create();
    }
}