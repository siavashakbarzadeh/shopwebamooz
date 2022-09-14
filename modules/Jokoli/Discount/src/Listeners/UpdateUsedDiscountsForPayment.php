<?php

namespace Jokoli\Discount\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Jokoli\Course\Models\Course;
use Jokoli\Course\Repository\CourseRepository;

class UpdateUsedDiscountsForPayment
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle($event)
    {
        $event->payment->discounts()
            ->update([
                'uses' => DB::raw('uses + 1'),
                'usage_limitation' => DB::raw('GREATEST(usage_limitation - 1, 0)'),
            ]);
    }
}
