<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Film;
use App\Models\Genre;
use Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Film>
 */
class FilmFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        $image = $this->faker->image(storage_path('app/images'), 480, 640, null, false);
        $imagePath = 'images/' . $image;

        $sourceVideo = 'samples/sample_video.mp4';
        $destinationVideo = 'videos/' . $this->faker->uuid . '.mp4';
        Storage::copy($sourceVideo,  $destinationVideo);

        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'director' => $this->faker->name,
            'release_year' => $this->faker->year,
            'price' => $this->faker->numberBetween(20, 300) * 1000,
            'duration' => $this->faker->numberBetween(60, 180) * 60,
            'cover_image_url' => $imagePath,
            'video_url' => $destinationVideo,
        ];
    }   
}
