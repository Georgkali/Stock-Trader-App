<?php

namespace App\Models;

class Quote
{
    private float $open;
    private float $close;
    private float $current;

    public function __construct(float $open, float $close, float $current)
{
    $this->open = $open;
    $this->close = $close;
    $this->current = $current;
}


    public function getOpen(): float
    {
        return $this->open;
    }

    public function getClose(): float
    {
        return $this->close;
    }

    public function getCurrent(): float
    {
        return $this->current;
    }
}
