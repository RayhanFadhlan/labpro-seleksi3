<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Film;
use App\Models\Genre;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
    
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

     
        DB::table('film_genre')->truncate();
        DB::table('films')->truncate();
        DB::table('genres')->truncate();


        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    
        $genres = Genre::factory(2)->create();

     
        Film::factory(10)
            ->hasAttached($genres->random(2)) 
            ->create();

        
        // User::factory(10)->create();
    }
}