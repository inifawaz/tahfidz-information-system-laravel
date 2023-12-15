<?php

namespace Tests\Feature\Api;

use App\Models\Level;
use App\Models\User;
use Database\Seeders\LevelSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LevelTest extends TestCase
{
    use RefreshDatabase;
    private $user;
    public function setUp(): void
    {
        parent::setUp();
        $this->seed([
            RoleSeeder::class,
            UserSeeder::class,

        ]);
        $this->user = User::where('email', 'admin@gmail.com')->first();
        Sanctum::actingAs($this->user);
    }


    public function test_user_can_get_all_levels()
    {
        $this->seed(LevelSeeder::class);
        $response = $this->getJson('/api/levels')
            ->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_admin_can_create_new_level()
    {
        $response = $this->postJson('/api/levels', [
            'name' => 'CCC',
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

        ])->assertStatus(201)->assertJson([
            'data' => [
                'name' => 'CCC',
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
            ]
        ]);
    }

    public function test_user_can_get_level_details()
    {
        $level = Level::factory()->create();
        $response = $this->getJson("/api/levels/{$level->id}")->assertStatus(200)->assertJson([
            'data' => [
                'name' => $level->name
            ]
        ]);
    }

    public function test_admin_can_update_level_details()
    {
        $level = Level::factory()->create();
        $response = $this->putJson("/api/levels/{$level->id}", [
            'name' => 'Edited'
        ])->assertStatus(200)->assertJson([
            'data' => [
                'name' => 'Edited'
            ]
        ]);
    }

    public function test_admin_can_delete_level()
    {
        $level = Level::factory()->create();
        $response = $this->deleteJson("/api/levels/{$level->id}")->assertStatus(200);
        $this->assertDatabaseMissing('levels', [
            'name' => $level->name
        ]);
    }

    public function test_check_numbering_level_after_admin_can_update_level_details()
    {
        $level_1 = Level::factory()->create();
        $level_2 = Level::factory()->create();
        $level_3 = Level::factory()->create();
        $level_4 = Level::factory()->create();
        $level_5 = Level::factory()->create();

        $response = $this->putJson("/api/levels/{$level_3->id}", [
            'number' => 5
        ])->assertStatus(200);

        $level_4->refresh();
        $level_5->refresh();

        $this->assertEquals(3, $level_4->number);
        $this->assertEquals(4, $level_5->number);
    }

    public function test_check_numbering_level_after_admin_can_delete_level()
    {
        $level_1 = Level::factory()->create();
        $level_2 = Level::factory()->create();
        $level_3 = Level::factory()->create();
        $level_4 = Level::factory()->create();
        $level_5 = Level::factory()->create();

        $response = $this->deleteJson("/api/levels/{$level_3->id}")->assertStatus(200);
        $this->assertDatabaseMissing('levels', [
            'id' => $level_3->id
        ]);

        $level_4->refresh();
        $level_5->refresh();

        $this->assertEquals(3, $level_4->number);
        $this->assertEquals(4, $level_5->number);
    }
}
