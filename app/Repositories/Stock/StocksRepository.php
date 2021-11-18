<?php

namespace App\Repositories\Stock;

interface StocksRepository
{
public function getCompanyBySymbol(string $symbol);
}
