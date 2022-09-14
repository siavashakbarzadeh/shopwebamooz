<?php

namespace Jokoli\Payment\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Jokoli\Payment\Events\PaymentWasSuccessful;
use Jokoli\Payment\Listeners\AddSellerShareToHisAccount;
use Jokoli\Payment\Listeners\RegisterUserInTheCourse;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        PaymentWasSuccessful::class => [
//            RegisterUserInTheCourse::class,
//            AddSellerShareToHisAccount::class,
        ],
    ];

    public function boot()
    {

    }
}
