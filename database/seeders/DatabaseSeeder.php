<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Tour;
use App\Models\Travel;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Tour::factory()
            ->count(20)
            ->for($travel = Travel::factory()->state(['is_public' => true])->create())
            ->state(new Sequence(
                ['starting_date' => now(), 'ending_date' => now()->addDays($travel->number_of_days),],
                ['starting_date' => now()->addDays(2), 'ending_date' => now()->addDays(2 + $travel->number_of_days),]
            ))->create();

        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
