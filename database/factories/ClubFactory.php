<?php

namespace Database\Factories;

use App\Models\Club;
use App\Models\Division;
use App\Models\Season;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Club>
 */
class ClubFactory extends Factory
{
    protected $model = Club::class;

    public function definition(): array
    {
        $name = fake()->company();

        return [
            'season_id' => Season::factory(),
            'division_id' => Division::factory(),
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(1, 9999),
            'logo_path' => null,
            'is_active' => true,
        ];
    }
}
