<?php

namespace Jokoli\Payment\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Arr;
use Jokoli\Course\Models\Course;
use Jokoli\Course\Repository\CourseRepository;

class RegisterUserInTheCourse
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
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        if (Arr::get(Relation::$morphMap,$event->payment->paymentable_type) == Course::class){
            resolve(CourseRepository::class)->addStudentToCourse($event->payment->paymentable,$event->payment->buyer_id);
        }
    }
}
