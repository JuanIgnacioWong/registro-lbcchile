<?php

namespace Database\Factories;

use App\Models\Club;
use App\Models\Division;
use App\Models\Season;
use App\Models\Submission;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Submission>
 */
class SubmissionFactory extends Factory
{
    protected $model = Submission::class;

    public function definition(): array
    {
        return [
            'season_id' => Season::factory(),
            'division_id' => Division::factory(),
            'club_id' => Club::factory(),
            'responsible_name' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->safeEmail(),
            'payment_status' => 'pending',
            'active_version' => null,
            'max_allowed_submissions' => 2,
        ];
    }
}
