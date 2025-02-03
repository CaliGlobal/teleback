<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\NewsCast;
use App\Models\Shows;
use App\Models\LatestEpisode;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Generate dummy data for the tables
        NewsCast::factory(10)->create(); // 10 news items
        
        Shows::factory(5)->create();     // 5 shows
        LatestEpisode::factory(5)->create(); // 5 latest episodes
    }
}
