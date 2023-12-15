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

class RegistrationTest extends TestCase
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

    public function test_admin_can_create_new_registration()
    {
        $period = Period::factory()->create();
        $level = Level::factory()->create();
        $student_1 = Student::factory()->create();
        $student_2 = Student::factory()->create();
        $student_3 = Student::factory()->create();
        $response = $this->postJson('/api/registrations', [
            'period_id' => $period->id,
            'level_id' => $level->id,
            'student_ids' => [
                $student_1->id,
                $student_2->id,
                $student_3->id,
            ]
        ])->assertStatus(201);

        $this->assertDatabaseHas('registrations', [
            'period_id' => $period->id,
            'level_id' => $level->id,
            'student_id' => $student_1->id
        ]);
    }

    public function test_admin_can_delete_existing_registration()
    {
        $period = Period::factory()->create();
        $level = Level::factory()->create();
        $student_1 = Student::factory()->create();
        $student_2 = Student::factory()->create();
        $student_3 = Student::factory()->create();
        $a = $this->postJson('/api/registrations', [
            'period_id' => $period->id,
            'level_id' => $level->id,
            'student_ids' => [
                $student_1->id,
                $student_2->id,
                $student_3->id,
            ]
        ]);

        $response = $this->deleteJson('/api/registrations', [
            'period_id' => $period->id,
            'level_id' => $level->id,
            'student_ids' => [
                $student_1->id,
                $student_2->id,
            ]
        ])->assertStatus(200);
        $this->assertDatabaseMissing('registrations', [
            'period_id' => $period->id,
            'level_id' => $level->id,
            'student_id' => [
                $student_1->id,
                $student_2->id,

            ]
        ]);

        $this->assertDatabaseHas('registrations', [
            'period_id' => $period->id,
            'level_id' => $level->id,
            'student_id' => [
                $student_3->id,
            ]
        ]);
    }
}
