<?php

namespace Database\Factories;

use App\Models\Division;
use App\Models\Season;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Division>
 */
class DivisionFactory extends Factory
{
    protected $model = Division::class;

    public function definition(): array
    {
        $name = fake()->randomElement(['Primera Division', 'Segunda Division', 'Sub 17', 'Sub 15']);

        return [
            'season_id' => Season::factory(),
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(1, 9999),
            'is_active' => true,
        ];
    }
}
