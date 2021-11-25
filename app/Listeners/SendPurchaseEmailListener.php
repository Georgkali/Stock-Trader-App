<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendPurchaseEmailListener implements ShouldQueue
{
   use InteractsWithQueue;


    public function handle($event)
    {
        Mail::to(User::query()->where('user_id', auth()->id())->value('email'))->send();
    }
}
