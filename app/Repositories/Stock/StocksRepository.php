<?php

namespace App\Repositories\Stock;

use App\Models\Company;
use App\Models\Quote;

interface StocksRepository
{
    public function getCompanyBySymbol(string $symbol): Company;

    public function getQuote(Company $company): Quote;

}
