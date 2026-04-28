<?php

namespace Database\Factories;

use App\Models\Submission;
use App\Models\SubmissionVersion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SubmissionVersion>
 */
class SubmissionVersionFactory extends Factory
{
    protected $model = SubmissionVersion::class;

    public function definition(): array
    {
        return [
            'submission_id' => Submission::factory(),
            'version_number' => 1,
            'club_logo_path' => null,
            'payment_receipt_path' => null,
            'players_roster_path' => null,
            'observations' => fake()->optional()->sentence(),
            'status' => 'received',
        ];
    }
}
