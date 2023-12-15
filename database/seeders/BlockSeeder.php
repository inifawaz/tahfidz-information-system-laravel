<?php

namespace Database\Seeders;

use App\Models\Block;
use App\Models\Part;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BlockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $partIds = Part::orderBy('number')->pluck('id');
        foreach ($partIds as $partId) {
            for ($number  = 1; $number <= 96; $number++) {
                Block::create([
                    'part_id' => $partId,
                    'number' => $number
                ]);
            }
        }
    }
}
