<?php

namespace Database\Factories;

use App\Models\LatestEpisode;
use App\Models\Shows;
use Illuminate\Database\Eloquent\Factories\Factory;

class LatestEpisodeFactory extends Factory
{
    protected $model = LatestEpisode::class;

    public function definition()
    {
        return [
            'show_id' => Shows::factory(), // Using ShowsFactory to generate a related show
            'description' => $this->faker->text,
            'thumbnailPath' => $this->faker->imageUrl(),
            'url' => $this->faker->url,
        ];
    }
}
