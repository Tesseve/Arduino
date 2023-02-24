<?php

namespace Database\Factories;

use App\Models\Score;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScoreFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Score::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'value' => $this->faker->randomNumber,
            'mode' => $this->faker->randomElement(['20', '30', '50']),
            'player_id' => \App\Models\Player::factory(),
        ];
    }
}
