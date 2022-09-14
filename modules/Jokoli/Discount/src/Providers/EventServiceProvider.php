<?php

namespace Jokoli\Discount\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Jokoli\Discount\Listeners\UpdateUsedDiscountsForPayment;
use Jokoli\Payment\Events\PaymentWasSuccessful;
use Jokoli\Payment\Listeners\AddSellerShareToHisAccount;
use Jokoli\Payment\Listeners\RegisterUserInTheCourse;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        PaymentWasSuccessful::class => [
            UpdateUsedDiscountsForPayment::class,
        ],
    ];

    public function boot()
    {

    }
}
