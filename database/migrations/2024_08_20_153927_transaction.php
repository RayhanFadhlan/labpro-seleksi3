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
        Schema::create("transactions", function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->uuid("user_id");
            $table->uuid("film_id");
            $table->timestamps();
            $table->foreign("user_id")->references("id")->on("users")->onDelete("cascade");
            $table->foreign("film_id")->references("id")->on("films")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists("transactions");
    }
};
