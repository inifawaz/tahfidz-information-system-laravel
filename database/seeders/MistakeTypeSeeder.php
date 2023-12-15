<?php

namespace Database\Seeders;

use App\Models\MistakeType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MistakeTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MistakeType::create([
            'name' => 'Huruf'
        ]);

        MistakeType::create([
            'name' => 'Harakat'
        ]);

        MistakeType::create([
            'name' => 'Kata'
        ]);

        MistakeType::create([
            'name' => 'Panjang Pendek'
        ]);

        MistakeType::create([
            'name' => 'Tajwid'
        ]);
    }
}
