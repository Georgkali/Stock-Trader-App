<?php

namespace App\Listeners;

use App\Events\NotificationButtonClicked;
use App\Mail\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendNotificationEmail implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct()
    {
        //
    }

    public function handle(NotificationButtonClicked $event)
    {
        Mail::to($event->getEmail())->send(new Notification());
    }
}
