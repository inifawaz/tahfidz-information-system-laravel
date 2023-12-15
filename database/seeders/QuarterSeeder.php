<?php

namespace Database\Seeders;

use App\Models\Part;
use App\Models\Quarter;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuarterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $partIds = Part::orderBy('number')->pluck('id');

        foreach ($partIds as $partId) {
            $number = 1;
            for ($hizb = 1; $hizb <= 2; $hizb++) {
                for ($rub = 1; $rub <= 4; $rub++) {
                    Quarter::create([
                        'part_id' => $partId,
                        'number' => $number,
                        'hizb' => $hizb,
                        'rub' => $rub
                    ]);
                    $number++;
                }
            }
        }
    }
}
