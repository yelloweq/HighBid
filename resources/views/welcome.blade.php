<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Auctions') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form hx-post="/api/auction/create" hx-target="#formsuccess">
                        @csrf
                        <label for="title">{{ __('Title') }}</label>
                        <input type="text" id="title" name="title" class="dark:white dark:bg-gray-800" />
                        <div id="title-error"></div>

                        <label for="description">{{ __('Description') }}</label>
                        <input type="text" id="description" name="description" class="dark:white dark:bg-gray-800" />
                        <div id="description-error"></div>

                        <label for="features">{{ __('Features') }}</label>
                        <textarea id="features" name="features" class="dark:white dark:bg-gray-800"></textarea>
                        <div id="features-error"></div>

                        <label for="auction-type">{{ __('Auction type') }}</label>
                        <select name="auction-type" id="auction-type" class="dark:white dark:bg-gray-800">
                            @foreach ($auctionTypes as $auctionType)
                                <option value="{{ $auctionType }}">{{ $auctionType->name }}</option>
                            @endforeach
                        </select>
                        <div id="auction-type-error"></div>

                        <label for="delivery-type">{{ __('Delivery type') }}</label>
                        <select name="delivery-type" id="delivery-type" class="dark:white dark:bg-gray-800">
                            @foreach ($deliveryTypes as $deliveryType)
                                <option value="{{ $deliveryType }}">{{ $deliveryType->name }}</option>
                            @endforeach
                        </select>
                        <div id="delivery-type-error"></div>

                        <label for="price">{{ __('Price') }}</label>
                        <input type="text" id="price" name="price" class="dark:white dark:bg-gray-800" />
                        <div id="price-error"></div>

                        <label for="start_time">{{ __('Start time') }}</label>
                        <input type="datetime-local" id="start-time" name="start-time"
                            class="dark:white dark:bg-gray-800" />
                        <div id="start-time-error"></div>

                        <label for="end_time">{{ __('End time') }}</label>
                        <input type="datetime-local" id="end-time" name="end-time"
                            class="dark:white dark:bg-gray-800" />
                        <div id="end-time-error"></div>

                        <button type="submit">{{ __('Create') }}</button>
                    </form>
                    <div id="formsuccess"></div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
