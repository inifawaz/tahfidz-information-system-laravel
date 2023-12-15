<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Level>
 */
class LevelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->sentence(3),
            'group_capacity' => 8,
            'revision_task_type' => 'setoran sempurna',
            'revision_quarter_portion' => 4,
            'connection_block_portion' => 20,
            'memorization_block_portion' => 5,
            'max_promotion_recitation_mistake' => 5,
            'max_promotion_question_mistake' => 3,
            'max_revision_recitation_mistake' => 3,
            'max_revision_question_mistake' => 3,
            'max_memorization_mistake' => 3
        ];
    }
}
