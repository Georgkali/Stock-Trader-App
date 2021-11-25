<?php

namespace App\Events;

use App\Models\Purchase;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;

class SendSellingEmail
{
    use Dispatchable,  SerializesModels;

    private Purchase $purchase;
    private Request $request;

    public function __construct(Purchase $purchase, Request $request)
    {
        $this->purchase = $purchase;
        $this->request = $request;
    }

    public function getPurchase(): Purchase
    {
        return $this->purchase;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }
}
