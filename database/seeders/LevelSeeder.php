<?php

namespace Database\Seeders;

use App\Models\Level;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Level::create([
            'name' => 'X',
            'group_capacity' => 8,
            'revision_task_type' => 'acak',
            'revision_quarter_portion' => 4,
            'connection_block_portion' => 20,
            'memorization_block_portion' => 5,
            'max_promotion_recitation_mistake' => 5,
            'max_promotion_question_mistake' => 3,
            'max_revision_recitation_mistake' => 3,
            'max_revision_question_mistake' => 3,
            'max_memorization_mistake' => 3

        ]);
        Level::create([
            'name' => 'XI',
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
        ]);
        Level::create([
            'name' => 'XII',
            'group_capacity' => 8,
            'revision_task_type' => 'pertanyaan',
            'revision_quarter_portion' => 4,
            'connection_block_portion' => 20,
            'memorization_block_portion' => 5,
            'max_promotion_recitation_mistake' => 5,
            'max_promotion_question_mistake' => 3,
            'max_revision_recitation_mistake' => 3,
            'max_revision_question_mistake' => 3,
            'max_memorization_mistake' => 3
        ]);
    }
}
