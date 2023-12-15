<?php

namespace App\Observers;

use App\Models\Period;

class PeriodObserver
{
    public function created(Period $period)
    {
        $period = $period->fresh();
        if ($period->active) {
            Period::withoutEvents(function () use ($period) {
                Period::whereNot('id', $period->id)->where('active', true)->update([
                    'active' => false
                ]);
            });
        }
    }

    public function updated(Period $period)
    {
        $period = $period->fresh();
        if ($period->active) {
            Period::withoutEvents(function () use ($period) {
                Period::whereNot('id', $period->id)->where('active', true)->update([
                    'active' => false
                ]);
            });
        }
    }
}
