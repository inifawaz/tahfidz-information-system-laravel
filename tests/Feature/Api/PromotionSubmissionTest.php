<?php

namespace Tests\Feature\Api;

use App\Models\Level;
use App\Models\Part;
use App\Models\Period;
use App\Models\Student;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PromotionSubmissionTest extends TestCase
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

    public function test_user_can_create_new_submission()
    {
        $part = Part::factory()->create();
        $period = Period::factory()->create();
        $level = Level::factory()->create();
        $student_1 = Student::factory()->create();

        $promotionPart = $this->postJson('/api/promotion-parts', [
            'part_id' => $part->id,
            'period_id' => $period->id,
            'level_id' => $level->id,
            'student_id' => $student_1->id
        ])->assertStatus(201)->json();

        $response = $this->postJson("/api/promotion-parts/{$promotionPart['data']['id']}/tasks/{$promotionPart['data']['tasks'][0]['id']}/submissions", [
            'duration' => '00:10:00'
        ])->assertStatus(201);
    }

    public function test_user_can_create_new_submission_for_current_task()
    {
        $part = Part::factory()->create();
        $period = Period::factory()->create();
        $level = Level::factory()->create();
        $student_1 = Student::factory()->create();

        $promotionPart = $this->postJson('/api/promotion-parts', [
            'part_id' => $part->id,
            'period_id' => $period->id,
            'level_id' => $level->id,
            'student_id' => $student_1->id
        ])->assertStatus(201)->json();

        $response = $this->postJson("/api/promotion-parts/{$promotionPart['data']['id']}/tasks/current/submissions", [
            'duration' => '00:10:00'
        ])->assertStatus(201);
    }
}
