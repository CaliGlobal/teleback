<?php

namespace Database\Factories;

use App\Models\Shows;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShowsFactory extends Factory
{
    protected $model = Shows::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'description' => $this->faker->text,
            'logo' => $this->faker->imageUrl(),
            'frequency' => $this->faker->randomElement(['daily', 'weekly', 'specific_days']),
            'days' => $this->faker->randomElement([['Monday', 'Wednesday'], null]),
            'start_time' => $this->faker->time(),
            'end_time' => $this->faker->time(),
        ];
    }
}
