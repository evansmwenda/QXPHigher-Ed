<?php

namespace App\Listeners;

use App\Events\MeetingCreatedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Mail\MeetingEmail;

class SendMeetingNotificationListener
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
     * @param  MeetingCreatedEvent  $event
     * @return void
     */
    public function handle(MeetingCreatedEvent $event)
    {
        //
        Mail::to($event->data['email'])->send(new MeetingEmail($event->data));
    }
}
