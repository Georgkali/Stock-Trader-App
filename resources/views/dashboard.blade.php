<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <p style="color: red">{{ $error }}</p>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(isset($errorMessage))
                        <p style="color: red">{{ $errorMessage }}</p>
                    @endif
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form method="get" action="{{route('view')}}">
                            @csrf
                            <input type="text" name="name">
                            <label for="name">Enter company name or symbol</label>
                            <br><br>
                            <button class="bg-gray-900 text-white px-3 py-2 rounded-md text-sm font-medium">search
                            </button>
                        </form>
                    </div>
                    <div class="p-6 bg-white border-b border-gray-200">
                        @if(isset($company) && isset($quote))
                            {{ $company->getName()  }}
                            <img src="{{$company->getLogoUrl()}}">
                            <ul>
                                <li>Current price: {{$quote->getCurrent()}}</li>
                                <li>Open price: {{$quote->getOpen()}}</li>
                                <li>Close price: {{$quote->getClose()}}</li>
                            </ul>
                            <form method="post"
                                  action="{{route('purchase.store', ['purchase' => new \App\Models\Purchase()])}}">
                                @csrf
                                <label for="stocksAmount">Enter amount of stocks You want to buy</label>
                                <input type="number" name="stocksAmount">
                                <input value="{{$company->getName()}}" name="companyName" hidden>
                                <input value="{{$quote->getCurrent()}}" name="currentPrice" hidden>
                                <br><br>
                                <button class="bg-gray-900 text-white px-3 py-2 rounded-md text-sm font-medium">
                                    Purchase
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
