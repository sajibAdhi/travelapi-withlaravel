<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\Travel;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTourStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_user_cannot_access_adding_tour(): void
    {
        $travel = Travel::factory()->create();
        $response = $this->postJson("/api/v1/admin/travels/$travel->id/tours");

        $response->assertStatus(401);
    }

    public function test_non_admin_user_cannot_access_adding_tour(): void
    {
        $this->seed(RoleSeeder::class);
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('name', 'editor')->value('id'));

        $travel = Travel::factory()->create();
        $response = $this->actingAs($user)->postJson("/api/v1/admin/travels/$travel->id/tours");

        $response->assertStatus(403);
    }

    public function test_admin_can_saves_tour_with_valid_data(): void
    {
        $this->seed(RoleSeeder::class);
        $role = Role::where('name', 'admin')->first();
        $user = User::factory()->create();
        $user->roles()->attach($role->id);

        $travel = Travel::factory()->create();
        $response = $this->actingAs($user)->postJson("/api/v1/admin/travels/$travel->id/tours", [
            'name' => 'Tour Name',
        ]);

        $response->assertStatus(422);

        $response = $this->actingAs($user)->postJson("/api/v1/admin/travels/$travel->id/tours", [
            'name' => 'Tour name',
            'starting_date' => now(),
            'ending_date' => now(),
            'price' => -100,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('ending_date');
        $response->assertJsonValidationErrorFor('price');

        $response = $this->actingAs($user)->postJson("/api/v1/admin/travels/$travel->id/tours", [
            'name' => 'Tour name',
            'starting_date' => now(),
            'ending_date' => now()->addDay(),
            'price' => 100,
        ]);

        $response->assertStatus(201);

        $response = $this->get("api/v1/travels/$travel->slug/tours");

        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => 'Tour name']);
    }
}
