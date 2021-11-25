<?php

namespace App\Listeners;

use App\Mail\Sell;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendPurchaseEmailListener
{



    public function handle($event)
    {
        Mail::to(User::query()->where('id', auth()->id())->value('email'))->send(new Sell());
    }
}
