<?php

namespace Tests\Feature;

use App\Models\Travel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TravelsListTest extends TestCase
{
    use RefreshDatabase;

    public function test_travels_list_return_paginated_data_correctly(): void
    {
        Travel::factory(16)->create(['is_public' => true]);

        $response = $this->get('/api/v1/travels');

        $response->assertStatus(200);
        $response->assertJsonCount(15, 'data');
        $response->assertJsonPath('meta.last_page', 2);
    }

    public function test_travels_list_onlty_shows_public_records(): void
    {
        $public_travels = Travel::factory(10)->create(['is_public' => true]);
        Travel::factory(10)->create(['is_public' => false]);

        $response = $this->get('/api/v1/travels');

        $response->assertStatus(200);
        $response->assertJsonCount(10, 'data');
        $response->assertJsonPath('data.0.name', $public_travels[0]->name);
    }
}
