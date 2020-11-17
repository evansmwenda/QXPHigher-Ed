<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        // 'App\Events\NewUserRegisteredEvent' => [
        //     'App\Listeners\AwardFreeTrialListener',
        // ],
        'Illuminate\Auth\Events\Registered' => [
            'App\Listeners\SendRegistrationEmailListener',
            'App\Listeners\AwardFreeTrialListener',
        ],
        'App\Events\PaymentSuccessfulEvent' => [
            'App\Listeners\SendPaymentNotificationListener',
        ],
        'App\Events\MeetingCreatedEvent' => [
            'App\Listeners\SendMeetingNotificationListener',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
