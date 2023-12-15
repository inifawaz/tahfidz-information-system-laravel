<?php

namespace App\Observers;

use App\Models\Group;

class GroupObserver
{
    public function creating(Group $group)
    {
        $lastNumber = Group::where('period_id', $group->period_id)->where('level_id', $group->level_id)->max('number');
        $group->number = $lastNumber ? $lastNumber + 1 : 1;
    }

    public function updating(Group $group): void
    {
        $originalGroup = $group->getOriginal();
        $lastNumber = Group::where('period_id', $group->period_id)->where('level_id', $group->level_id)->max('number');
        if ($group->number > $lastNumber) {
            $group->number = $lastNumber;
        }
        $newNumber = $group->number;
        $originalNumber = $originalGroup['number'];
        $groupsToUpdate = Group::where('period_id', $group->period_id)->where('level_id', $group->level_id)->whereNot('id', $group->id)->get();
        foreach ($groupsToUpdate as $item) {
            Group::withoutEvents(function () use ($item, $newNumber, $originalNumber) {
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

    public function deleted(Group $group): void
    {

        $groups = Group::where('period_id', $group->period_id)->where('level_id', $group->level_id)->orderBy('number')->get();
        $number = 1;
        foreach ($groups as $group) {
            $group->number = $number;
            $group->save();
            $number++;
        }
    }
}
