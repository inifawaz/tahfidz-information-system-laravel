<?php

namespace Tests\Feature\Api;

use App\Models\Level;
use App\Models\Period;
use App\Models\Student;
use App\Models\User;
use Database\Seeders\LevelSeeder;
use Database\Seeders\PeriodSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\StudentSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StudentTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed([
            RoleSeeder::class,
            UserSeeder::class,
        ]);
        Sanctum::actingAs(
            User::where('email', 'admin@gmail.com')->first()
        );
    }

    public function test_user_can_get_all_students()
    {
        $this->seed(StudentSeeder::class);
        $response = $this->getJson('/api/students')->assertStatus(200)->assertJsonCount(36, 'data');
    }

    public function test_admin_can_create_new_student()
    {

        $period = Period::factory()->create();
        $level = Level::factory()->create();
        $response = $this->postJson('/api/students', [
            "period_id" => $period->id,
            "level_id" => $level->id,
            "name" => "Putri",
            "date_of_birth" => "1999-06-28",
            "gender" => "perempuan"
        ])->assertStatus(201)->assertJsonStructure([
            'message',
            'data' => [
                'registrations'
            ]
        ]);
    }

    public function test_user_can_get_student_details()
    {
        $student = Student::factory()->create();
        $response = $this->getJson("/api/students/{$student->id}")->assertStatus(200);
    }

    public function test_admin_can_update_student_details()
    {
        $student = Student::factory()->create();
        $response = $this->putJson("/api/students/{$student->id}", [
            'name' => 'Edited'
        ])->assertStatus(200)->assertJson([
            'data' => [
                'name' => 'Edited'
            ]
        ]);
    }

    public function test_admin_can_delete_user()
    {
        $student = Student::factory()->create();
        $response = $this->deleteJson("/api/students/{$student->id}")->assertStatus(200);
    }
}
