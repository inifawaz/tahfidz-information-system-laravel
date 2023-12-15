<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            PeriodSeeder::class,
            LevelSeeder::class,
            StudentSeeder::class,
            PartSeeder::class,
            ChapterSeeder::class,
            VerseSeeder::class,
            QuarterSeeder::class,
            // BlockSeeder::class,
            MistakeTypeSeeder::class
        ]);
    }
}
