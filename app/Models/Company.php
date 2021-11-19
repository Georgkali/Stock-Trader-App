<?php

namespace App\Models;

class Company
{

    private string $name;
    private string $logoUrl;
    private string $symbol;

    public function __construct(string $name, string $symbol, string $logoUrl)
    {
        $this->name = $name;
        $this->logoUrl = $logoUrl;
        $this->symbol = $symbol;
    }


    public function getName(): string
    {
        return $this->name;
    }


    public function getLogoUrl(): string
    {
        return $this->logoUrl;
    }


    public function getSymbol(): string
    {
        return $this->symbol;
    }
}
