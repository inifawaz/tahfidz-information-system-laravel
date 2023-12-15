<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserTest extends TestCase
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

    public function test_user_can_get_all_users()
    {
        $response = $this->getJson('/api/users')->assertStatus(200)->assertJsonCount(3, 'data');
    }

    public function test_admin_can_create_new_user()
    {
        $response = $this->postJson('/api/users', [
            'name' => 'Test User',
            'email' => 'test@gmail.com',
            'password' => '12345678'
        ])->assertStatus(201);
    }



    public function test_user_can_get_user_details()
    {
        $response = $this->getJson("/api/users/{$this->user->id}")->assertStatus(200)->assertJson([
            'data' => [
                'email' => 'admin@gmail.com'
            ]
        ]);
    }

    public function test_admin_can_update_user()
    {
        $userToUpdate = User::where('email', 'teacher@gmail.com')->first();

        $this->assertEquals('Teacher', $userToUpdate->name);

        $response = $this->putJson("/api/users/{$userToUpdate->id}", [
            'name' => 'Teacher User Edited',
            'roles' => [
                'teacher'
            ]
        ])->assertStatus(200)->assertJson([
            'data' => [
                'name' => 'Teacher User Edited'
            ]
        ]);
    }

    public function test_admin_can_delete_user()
    {
        $userToDelete = User::where('email', 'teacher@gmail.com')->first();
        $response = $this->deleteJson("/api/users/{$userToDelete->id}")->assertStatus(200);

        $this->assertDatabaseMissing('users', [
            'id' => $userToDelete->id
        ]);
    }

    public function test_user_can_get_current_user_details()
    {
        $response = $this->getJson("/api/users/me")->assertStatus(200)->assertJson([
            'data' => [
                'email' => 'admin@gmail.com'
            ]
        ]);
    }

    public function test_user_can_update_current_user_details()
    {
        $this->assertEquals('Admin', $this->user->name);
        $response = $this->putJson("/api/users/me", [
            'name' => 'Admin Edited',
        ])->assertStatus(200)->assertJson([
            'data' => [
                'name' => 'Admin Edited'
            ]
        ]);
    }
}
