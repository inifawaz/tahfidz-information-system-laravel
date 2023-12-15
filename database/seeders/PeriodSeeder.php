<?php

namespace Database\Seeders;

use App\Models\Period;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Period::create([
            'name' => 'Tahun Pelajaran 2021/2022'
        ]);

        Period::create([
            'name' => 'Tahun Pelajaran 2022/2023'
        ]);

        Period::create([
            'name' => 'Tahun Pelajaran 2023/2024'
        ]);
    }
}
