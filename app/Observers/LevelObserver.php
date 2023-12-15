<?php

namespace App\Observers;

use App\Models\Level;

class LevelObserver
{
    public function creating(Level $level)
    {
        $lastNumber = Level::max('number');
        $level->number = $lastNumber ? $lastNumber + 1 : 1;
    }

    public function updating(Level $level): void
    {
        $originalLevel = $level->getOriginal();
        $lastNumber = Level::max('number');
        if ($level->number > $lastNumber) {
            $level->number = $lastNumber;
        }
        $newNumber = $level->number;
        $originalNumber = $originalLevel['number'];
        $levelsToUpdate = Level::whereNot('id', $level->id)->get();
        foreach ($levelsToUpdate as $item) {
            Level::withoutEvents(function () use ($item, $newNumber, $originalNumber) {
                if ($newNumber > $originalNumber) {
                    if ($item->number <= $newNumber && $item->number > $originalNumber) {
                        $item->decrement('number');
                    }
                } else {
                    if ($item->number >= $newNumber && $item->number < $originalNumber) {
                        $item->increment('number');
                    }
                }
            });
        }
    }

    public function deleted(): void
    {
        $levels = Level::orderBy('number')->get();
        $number = 1;
        foreach ($levels as $level) {
            $level->number = $number;
            $level->save();
            $number++;
        }
    }
}
