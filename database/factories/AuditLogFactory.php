<?php

namespace Database\Factories;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AuditLog>
 */
class AuditLogFactory extends Factory
{
    protected $model = AuditLog::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'action' => 'sample_action',
            'entity_type' => 'sample_entity',
            'entity_id' => fake()->numberBetween(1, 1000),
            'description' => fake()->sentence(),
            'meta' => ['ip' => fake()->ipv4()],
        ];
    }
}
