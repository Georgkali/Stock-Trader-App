<?php

namespace App\Http\Controllers;


use App\Repositories\Stock\StocksRepository;
use App\Services\BusinessHoursService;
use Illuminate\Http\Request;


class StocksController extends Controller
{
    private StocksRepository $stocksRepository;

    public function __construct(StocksRepository $stocksRepository)
    {
        $this->stocksRepository = $stocksRepository;
    }


    public function view(Request $request)
    {

        $request->validate([
            'name' => 'required'
        ]);
        try {
            $symbol = $this->stocksRepository->getCompany($request->get('name'));
            $company = $this->stocksRepository->getCompanyBySymbol($symbol);
            $quote = $this->stocksRepository->getQuote($company);
        } catch (\Throwable $exception) {

            return view('dashboard', ['errorMessage' => 'invalid input']);
        }

        if ((new BusinessHoursService())->now()) {
            return view('dashboard', ['company' => $company, 'quote' => $quote]);
        }

        return view('dashboard', ['company' => $company, 'quote' => $quote, 'holiday' => true]);
    }
}
