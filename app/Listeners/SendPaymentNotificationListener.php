<?php

namespace App\Listeners;

use App\Events\PaymentSuccessfulEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use AfricasTalking\SDK\AfricasTalking;

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
        //send sms upon successful payment
        app('App\Http\Controllers\HomeController')->sendPaymentNotification($event->user);
    }
}
