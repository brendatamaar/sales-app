<?php

namespace App\Observers;

use App\Models\Deal;
use App\Models\DealReport;
use Illuminate\Support\Facades\Auth;

class DealObserver
{
    /**
     * Handle the Deal "created" event.
     *
     * @param  \App\Models\Deal  $deal
     * @return void
     */
    public function created(Deal $deal)
    {
        DealReport::create([
            'deals_id' => $deal->deals_id,
            'stage' => (string) $deal->stage,
            'created_date' => $deal->created_date,
            'closed_date' => $deal->closed_date,
            'updated_by' => optional(Auth::user())->id,
            'updated_at' => now(),
        ]);
    }

    /**
     * Handle the Deal "updated" event.
     *
     * @param  \App\Models\Deal  $deal
     * @return void
     */
    public function updated(Deal $deal)
    {
        if ($deal->wasChanged(['stage', 'created_date', 'closed_date'])) {
            DealReport::create([
                'deals_id' => $deal->deals_id,
                'stage' => (string) $deal->stage,
                'created_date' => $deal->created_date,
                'closed_date' => $deal->closed_date,
                'updated_by' => optional(Auth::user())->id,
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Handle the Deal "deleted" event.
     *
     * @param  \App\Models\Deal  $deal
     * @return void
     */
    public function deleted(Deal $deal)
    {
        //
    }

    /**
     * Handle the Deal "restored" event.
     *
     * @param  \App\Models\Deal  $deal
     * @return void
     */
    public function restored(Deal $deal)
    {
        //
    }

    /**
     * Handle the Deal "force deleted" event.
     *
     * @param  \App\Models\Deal  $deal
     * @return void
     */
    public function forceDeleted(Deal $deal)
    {
        //
    }
}
