<?php

namespace Database\Factories;

use App\Models\Season;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Season>
 */
class SeasonFactory extends Factory
{
    protected $model = Season::class;

    public function definition(): array
    {
        $year = fake()->numberBetween(2025, 2035);

        return [
            'year' => $year,
            'name' => "Temporada {$year}",
            'is_active' => fake()->boolean(80),
        ];
    }
}
