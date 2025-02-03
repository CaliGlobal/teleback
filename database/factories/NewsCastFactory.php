<?php

namespace Database\Factories;

use App\Models\NewsCast;
use Illuminate\Database\Eloquent\Factories\Factory;

class NewsCastFactory extends Factory
{
    protected $model = NewsCast::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->text,
            'thumbnailPath' => $this->faker->imageUrl(),
            'url' => $this->faker->url,
        ];
    }
}
