<?php

namespace Tests\Feature\Api;

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

class MembershipTest extends TestCase
{
    use RefreshDatabase;
    private $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed([
            RoleSeeder::class,
            UserSeeder::class
        ]);
        $this->user = User::where('email', 'admin@gmail.com')->first();
        Sanctum::actingAs($this->user);
    }

    public function test_admin_can_create_new_membership()
    {
        $period = Period::factory()->create();
        $level = Level::factory()->create();
        $user = User::factory()->create();
        $student_1 = Student::factory()->create();
        $student_2 = Student::factory()->create();
        $student_3 = Student::factory()->create();

        $group = $this->postJson('/api/groups', [
            'period_id' => $period->id,
            'level_id' => $level->id,
            'user_id' => $user->id,
            'student_ids' => [$student_1->id, $student_2->id]
        ])->assertStatus(201);

        $group = $group->json();
        $response = $this->postJson('/api/memberships', [
            'group_id' => $group['data']['id'],
            'student_ids' => [$student_3->id]
        ])->assertStatus(201);
    }

    public function test_admin_can_delete_membership()
    {
        $period = Period::factory()->create();
        $level = Level::factory()->create();
        $user = User::factory()->create();
        $student_1 = Student::factory()->create();
        $student_2 = Student::factory()->create();
        $student_3 = Student::factory()->create();

        $group = $this->postJson('/api/groups', [
            'period_id' => $period->id,
            'level_id' => $level->id,
            'user_id' => $user->id,
            'student_ids' => [$student_1->id, $student_2->id, $student_3->id]
        ])->assertStatus(201);

        $group = $group->json();
        $response = $this->deleteJson('/api/memberships', [
            'group_id' => $group['data']['id'],
            'student_ids' => [$student_3->id, $student_2->id]
        ])->assertStatus(200);

        $this->assertDatabaseMissing('memberships', [
            'group_id' => $group['data']['id'],
            'student_id' => $student_3->id
        ]);
    }
}
