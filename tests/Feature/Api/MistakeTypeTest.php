<?php

namespace Tests\Feature\Api;

use App\Models\MistakeType;
use App\Models\User;
use Database\Seeders\MistakeTypeSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class MistakeTypeTest extends TestCase
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

    public function test_user_can_get_all_mistake_types()
    {
        $this->seed([
            MistakeTypeSeeder::class
        ]);

        $response = $this->getJson('/api/mistake-types')->assertStatus(200)->assertJsonCount(5, 'data');
    }

    public function test_admin_can_create_new_mistake_type()
    {
        $response = $this->postJson('/api/mistake-types', [
            'name' => 'SSS'
        ])->assertStatus(201);

        $this->assertDatabaseHas('mistake_types', [
            'name' => 'SSS'
        ]);
    }

    public function test_user_can_get_mistake_type_details()
    {
        $mistakeType = MistakeType::factory()->create();

        $response = $this->getJson("/api/mistake-types/{$mistakeType->id}")->assertStatus(200)->assertJson([
            'data' => [
                'name' => $mistakeType->name
            ]
        ]);
    }

    public function test_admin_can_update_mistake_type_details()
    {
        $mistakeType = MistakeType::factory()->create();

        $response = $this->putJson("/api/mistake-types/{$mistakeType->id}", [
            'name' => 'edited'
        ])->assertStatus(200)->assertJson([
            'data' => [
                'name' => 'edited'
            ]
        ]);
    }

    public function test_admin_can_delete_mistake_type()
    {
        $mistakeType = MistakeType::factory()->create();
        $response = $this->deleteJson("/api/mistake-types/{$mistakeType->id}")->assertStatus(200);

        $this->assertDatabaseMissing('mistake_types', [
            'name' => $mistakeType->name
        ]);
    }
}
