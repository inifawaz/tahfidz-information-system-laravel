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

class PromotionPartTest extends TestCase
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

    public function test_user_can_get_all_promotion_parts()
    {
        $part = Part::factory()->create();
        $period = Period::factory()->create();
        $level = Level::factory()->create();
        $student_1 = Student::factory()->create();
        $student_2 = Student::factory()->create();
        $student_3 = Student::factory()->create();

        $this->postJson('/api/promotion-parts', [
            'part_id' => $part->id,
            'period_id' => $period->id,
            'level_id' => $level->id,
            'student_id' => $student_1->id
        ])->assertStatus(201);
        $this->postJson('/api/promotion-parts', [
            'part_id' => $part->id,
            'period_id' => $period->id,
            'level_id' => $level->id,
            'student_id' => $student_2->id
        ])->assertStatus(201);
        $this->postJson('/api/promotion-parts', [
            'part_id' => $part->id,
            'period_id' => $period->id,
            'level_id' => $level->id,
            'student_id' => $student_3->id
        ])->assertStatus(201);

        $response = $this->getJson('/api/promotion-parts')->assertStatus(200)->assertJsonCount(3, 'data');
    }

    public function test_admin_can_create_new_promotion_part()
    {
        $part = Part::factory()->create();
        $period = Period::factory()->create();
        $level = Level::factory()->create();
        $student_1 = Student::factory()->create();

        $response = $this->postJson('/api/promotion-parts', [
            'part_id' => $part->id,
            'period_id' => $period->id,
            'level_id' => $level->id,
            'student_id' => $student_1->id
        ])->assertStatus(201);
    }

    public function test_user_can_get_promotion_part_details()
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

        $response = $this->getJson("/api/promotion-parts/{$promotionPart['data']['id']}")->assertStatus(200);
    }

    public function test_user_can_delete_promotion_part()
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

        $response = $this->deleteJson("/api/promotion-parts/{$promotionPart['data']['id']}")->assertStatus(200);
    }
}
