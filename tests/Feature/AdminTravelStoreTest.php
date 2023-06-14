<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminTravelStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_user_cannot_access_adding_travel(): void
    {
        $response = $this->postJson('/api/v1/admin/travels');

        $response->assertStatus(401);
    }

    public function test_non_admin_user_cannot_access_adding_travel(): void
    {
        $this->seed(RoleSeeder::class);
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('name', 'editor')->value('id'));

        $response = $this->actingAs($user)->postJson('/api/v1/admin/travels');

        $response->assertStatus(403);
    }

    public function test_admin_can_saves_travel_with_valid_data(): void
    {
        $this->seed(RoleSeeder::class);
        $role = Role::where('name', 'admin')->first();
        $user = User::factory()->create();
        $user->roles()->attach($role->id);

        $response = $this->actingAs($user)->postJson('/api/v1/admin/travels', [
            'name' => 'Travel name',
        ]);

        $response->assertStatus(422);

        $response = $this->actingAs($user)->postJson('/api/v1/admin/travels', [
            'is_public' => true,
            'name' => 'Travel name',
            'description' => 'Travel description',
            'number_of_days' => 5,
        ]);

        $response->assertStatus(201);

        $response = $this->get('api/v1/travels');

        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => 'Travel name']);
    }
}
