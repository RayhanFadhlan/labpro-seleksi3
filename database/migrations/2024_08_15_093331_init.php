<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //
        Schema::create("users", function (Blueprint $table) {
            $table->uuid("id")->primary();    
            $table->string("username")->unique();
            $table->string("email")->unique();
            $table->string("first_name");
            $table->string("last_name");
            $table->string("password");
            $table->integer("balance")->default(0);
            $table->string("auth_token")->nullable();
            $table->string("role")->default("user");
            $table->timestamps();

        });

        Schema::create("films", function (Blueprint $table){
            $table->uuid("id")->primary();
            $table->string("title");
            $table->text("description");
            $table->string("director");
            $table->string("release_year");
            $table->string("price");
            $table->string("duration");
            $table->string("cover_image_url")->nullable();
            $table->string("video_url");
            $table->timestamps();
        });

        Schema::create("genres", function (Blueprint $table){
            $table->uuid("id")->primary();
            $table->string("name");
            $table->timestamps();
        });
        
        Schema::create("film_genre", function (Blueprint $table){
            $table->uuid("film_id");
            $table->uuid("genre_id");
            $table->timestamps();
            // $table->primary(["film_id", "genre_id"]);
            $table->foreign("film_id")->references("id")->on("films")->onDelete("cascade");
            $table->foreign("genre_id")->references("id")->on("genres")->onDelete("cascade");
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        // Schema::dropIfExists('users');
        Schema::dropIfExists('films');
        Schema::dropIfExists('genres');
        Schema::dropIfExists('film_genre');
    }
};
