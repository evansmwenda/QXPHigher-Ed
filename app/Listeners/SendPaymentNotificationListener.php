<?php

namespace App\Listeners;

use App\Events\PaymentSuccessfulEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendPaymentNotificationListener
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
     * @param  PaymentSuccessfulEvent  $event
     * @return void
     */
    public function handle(PaymentSuccessfulEvent $event)
    {
        //
        //$event->user;
        //give user free trial
        // DB::table('subscriptions')->insert(
        //     [
        //         "user_id" => $event->user->id,
        //         "expiry_on" => Date('Y-m-d h:i:s', strtotime('+14 days')),
        //         "package_id" => '0'
        //     ]
        // );
        app('App\Http\Controllers\HomeController')->sendPaymentNotification();
    }
}
