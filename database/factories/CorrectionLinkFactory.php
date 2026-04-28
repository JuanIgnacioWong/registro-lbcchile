<?php

namespace Database\Factories;

use App\Models\Club;
use App\Models\CorrectionLink;
use App\Models\Division;
use App\Models\Season;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<CorrectionLink>
 */
class CorrectionLinkFactory extends Factory
{
    protected $model = CorrectionLink::class;

    public function definition(): array
    {
        return [
            'season_id' => Season::factory(),
            'division_id' => Division::factory(),
            'club_id' => Club::factory(),
            'token' => Str::random(64),
            'is_active' => true,
            'expires_at' => now()->addDays(14),
        ];
    }
}
