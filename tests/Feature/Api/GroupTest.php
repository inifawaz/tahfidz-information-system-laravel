<?php

namespace Tests\Feature\Api;

use App\Models\Group;
use App\Models\Level;
use App\Models\Period;
use App\Models\Student;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GroupTest extends TestCase
{
    use RefreshDatabase;



    public function setUp(): void
    {
        parent::setUp();
        $this->seed([
            RoleSeeder::class,
            UserSeeder::class
        ]);
        $user = User::where('email', 'admin@gmail.com')->first();
        Sanctum::actingAs($user);
    }

    public function test_user_can_get_all_groups()
    {
        $period = Period::factory()->create();
        $level = Level::factory()->create();
        $user = User::factory()->create();
        for ($i = 0; $i < 3; $i++) {
            Group::create([
                'period_id' => $period->id,
                'level_id' => $level->id,
                'user_id' => $user->id
            ]);
        }
        $response = $this->getJson('/api/groups')->assertStatus(200)->assertJsonCount(3, 'data');
    }

    public function test_admin_can_create_new_group()
    {
        $period = Period::factory()->create();
        $level = Level::factory()->create();
        $user = User::factory()->create();
        $student_1 = Student::factory()->create();
        $student_2 = Student::factory()->create();
        $student_3 = Student::factory()->create();

        $response = $this->postJson('/api/groups', [
            'period_id' => $period->id,
            'level_id' => $level->id,
            'user_id' => $user->id,
            'user_ids' => [$student_1->id, $student_2->id, $student_3->id]
        ])->assertStatus(201);
    }

    public function test_user_can_get_group_details()
    {
        $period = Period::factory()->create();
        $level = Level::factory()->create();
        $user_1 = User::factory()->create();
        $group = Group::create([
            'period_id' => $period->id,
            'level_id' => $level->id,
            'user_id' => $user_1->id
        ]);

        $response = $this->getJson("/api/groups/{$group->id}")->assertStatus(200)->assertJson([
            'data' => [
                'user_id' => $user_1->id
            ]
        ]);
    }

    public function test_admin_can_update_group()
    {
        $period = Period::factory()->create();
        $level = Level::factory()->create();
        $user_1 = User::factory()->create();
        $group = Group::create([
            'period_id' => $period->id,
            'level_id' => $level->id,
            'user_id' => $user_1->id
        ]);
        $user_2 = User::factory()->create();

        $response = $this->putJson("/api/groups/{$group->id}", [
            'user_id' => $user_2->id
        ])->assertStatus(200)->assertJson([
            'data' => [
                'user_id' => $user_2->id
            ]
        ]);
    }

    public function test_admin_can_delete_group()
    {
        $period = Period::factory()->create();
        $level = Level::factory()->create();
        $user_1 = User::factory()->create();
        $group = Group::create([
            'period_id' => $period->id,
            'level_id' => $level->id,
            'user_id' => $user_1->id
        ]);

        $response = $this->deleteJson("/api/groups/{$group->id}")->assertStatus(200);
        $this->assertDatabaseMissing('groups', [
            'id' => $group->id
        ]);
    }

    public function test_check_numbering_group_after_admin_can_update_group_details()
    {
        $period = Period::factory()->create();
        $level = Level::factory()->create();
        $user_1 = User::factory()->create();
        $group_1 = Group::create([
            'period_id' => $period->id,
            'level_id' => $level->id,
            'user_id' => $user_1->id
        ]);
        $group_2 = Group::create([
            'period_id' => $period->id,
            'level_id' => $level->id,
            'user_id' => $user_1->id
        ]);
        $group_3 = Group::create([
            'period_id' => $period->id,
            'level_id' => $level->id,
            'user_id' => $user_1->id
        ]);
        $group_4 = Group::create([
            'period_id' => $period->id,
            'level_id' => $level->id,
            'user_id' => $user_1->id
        ]);
        $group_5 = Group::create([
            'period_id' => $period->id,
            'level_id' => $level->id,
            'user_id' => $user_1->id
        ]);

        $response = $this->putJson("/api/groups/{$group_3->id}", [
            'number' => 5
        ])->assertStatus(200);

        $group_4->refresh();
        $group_5->refresh();
        $this->assertEquals(3, $group_4->number);
        $this->assertEquals(4, $group_5->number);
    }

    public function test_check_numbering_level_after_admin_can_delete_group()
    {
        $period = Period::factory()->create();
        $level = Level::factory()->create();
        $user_1 = User::factory()->create();
        $group_1 = Group::create([
            'period_id' => $period->id,
            'level_id' => $level->id,
            'user_id' => $user_1->id
        ]);
        $group_2 = Group::create([
            'period_id' => $period->id,
            'level_id' => $level->id,
            'user_id' => $user_1->id
        ]);
        $group_3 = Group::create([
            'period_id' => $period->id,
            'level_id' => $level->id,
            'user_id' => $user_1->id
        ]);
        $group_4 = Group::create([
            'period_id' => $period->id,
            'level_id' => $level->id,
            'user_id' => $user_1->id
        ]);
        $group_5 = Group::create([
            'period_id' => $period->id,
            'level_id' => $level->id,
            'user_id' => $user_1->id
        ]);

        $response = $this->deleteJson("/api/groups/{$group_3->id}")->assertStatus(200);

        $group_4->refresh();
        $group_5->refresh();
        $this->assertEquals(3, $group_4->number);
        $this->assertEquals(4, $group_5->number);
    }
}
