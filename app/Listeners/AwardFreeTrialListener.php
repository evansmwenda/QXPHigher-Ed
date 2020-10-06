<?php

namespace App\Listeners;

use App\Events\NewUserRegisteredEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Events\Registered as MyEvent;
class AwardFreeTrialListener
{
    /**
     * Handle the event.
     *
     * @param  NewUserRegisteredEvent  $event
     * @return void
     */
    public function handle(MyEvent $event)
    {
        //$event->user;
        //give user free trial
        DB::table('subscriptions')->insert(
            [
                "user_id" => $event->user->id,
                "expiry_on" => Date('Y-m-d h:i:s', strtotime('+14 days')),
                "package_id" => '0'
            ]
        );
    }
}
