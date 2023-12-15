<?php

namespace Tests\Feature\Api;

use App\Models\Period;
use App\Models\User;
use Database\Seeders\PeriodSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PeriodTest extends TestCase
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

    public function test_user_can_get_all_periods()
    {
        $this->seed([
            PeriodSeeder::class
        ]);
        $response = $this->getJson('/api/periods')
            ->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_admin_can_create_new_period()
    {
        $response = $this->postJson('/api/periods', [
            'name' => 'Test Add New Period'
        ])->assertStatus(201)->assertJson([
            'data' => [
                'name' => 'Test Add New Period'
            ]
        ]);
    }

    public function test_user_can_get_period_details()
    {
        $period = Period::factory()->create();
        $response = $this->getJson("/api/periods/{$period->id}")->assertStatus(200)->assertJson([
            'data' => [
                'name' => $period->name
            ]
        ]);
    }

    public function test_admin_can_update_period()
    {
        $period = Period::factory()->create();
        $response = $this->putJson("/api/periods/{$period->id}", [
            'name' => 'Edited'
        ])->assertStatus(200)->assertJson([
            'data' => [
                'name' => 'Edited'
            ]
        ]);
    }

    public function test_admin_can_delete_period()
    {
        $period = Period::factory()->create();
        $response = $this->deleteJson("/api/periods/{$period->id}")->assertStatus(200);
        $this->assertDatabaseMissing('periods', [
            'name' => $period->name
        ]);
    }

    public function test_user_can_get_active_period_details()
    {
        $period = Period::factory()->create();
        $response = $this->getJson('/api/periods/active')->assertStatus(200)->assertJson([
            'data' => [
                'name' => $period->name,
                'active' => true
            ]
        ]);
    }

    public function test_admin_can_update_active_period_details()
    {
        $period = Period::factory()->create();
        $response = $this->putJson("/api/periods/active", [
            'name' => 'Edited'
        ])->assertStatus(200)->assertJson([
            'data' => [
                'name' => 'Edited'
            ]
        ]);
    }

    public function test_admin_can_delete_active_period()
    {
        $period = Period::factory()->create();
        $response = $this->deleteJson("/api/periods/active")->assertStatus(200);
        $this->assertDatabaseMissing('periods', [
            'name' => $period->name
        ]);
    }
}
