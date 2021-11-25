<?php

namespace App\Providers;

use App\Events\SendPurchaseEmail;
use App\Events\SendSellingEmail;
use App\Listeners\SendPurchaseEmailListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        SendPurchaseEmail::class => [
            SendPurchaseEmailListener::class
        ],
        SendSellingEmail::class => [
            SendPurchaseEmailListener::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
