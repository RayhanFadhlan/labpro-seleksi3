<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Film;
use App\Models\Genre;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

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
        $cloudflareR2Url = env('CLOUDFLARE_R2_URL');

    
        $tempImage = public_path('/samples/sample_image.png');
        $coverImagePath = Storage::disk()->putFile('images', new File($tempImage));
        $fullCoverImageUrl = $cloudflareR2Url .  $coverImagePath;

        
        $tempVideo = public_path('/samples/sample_video.mp4');
        $videoPath = Storage::disk('r2')->putFile('videos', $tempVideo);
        $fullVideoUrl = $cloudflareR2Url . $videoPath;

        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'director' => $this->faker->name,
            'release_year' => $this->faker->year,
            'price' => $this->faker->numberBetween(20, 300) * 1000,
            'duration' => $this->faker->numberBetween(60, 180) * 60,
            'cover_image_url' => $fullCoverImageUrl,
            'video_url' => $fullVideoUrl,
        ];
    }
}
