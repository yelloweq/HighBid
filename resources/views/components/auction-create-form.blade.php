<form 
    method="POST"
    action="{{ route('auction.create') }}" 
    hx-post="{{ route('auction.create') }}" 
    hx-swap="outerHTML" 
    hx-target="main"
    >
    @csrf
    @include('flatpickr::components.style')
    <div>
        <x-input-label for="title" :value="__('Title')" />
        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" required autofocus autocomplete="name" />
        <x-input-error class="mt-2" :messages="$errors->get('title')" />
    </div>
    <div>
        <x-input-label for="description" :value="__('Description')" />
        <x-text-input id="description" name="description" type="text" class="mt-1 block w-full" required autocomplete="Description" />
        <x-input-error class="mt-2" :messages="$errors->get('description')" />
    </div>
    <div>
        <x-input-label for="features" :value="__('Features')" />
        <x-text-input id="features" name="features" type="text" class="mt-1 block w-full" required autocomplete="Features" />
        <x-input-error class="mt-2" :messages="$errors->get('features')" />
    </div>
    <div>
        <x-input-label for="file" :value="__('Images')" />
        <x-text-input id="file" name="file" type="file" class="mt-1 block w-full" required autocomplete="Images" />
        <x-input-error class="mt-2" :messages="$errors->get('file')" />
    </div>
    <div>
        <div class="flex gap-2 align-middle">
            <x-input-label for="auction-type" :value="__('Type of auction')" />
            <x-tooltip>
                <div class="mb-4">
                    {{ __('Last bid: this is how last bid works') }}
                </div>
                {{ __('Time limit: this is how it works') }}
            </x-tooltip>
        </div>
        
        <x-select-input id="auction-type" name="auction-type" class="mt-1 block w-full" required>
            @foreach ($auctionTypes as $auctionType)
                <option value="{{ $auctionType }}">{{ $auctionType->name }}</option>
            @endforeach
        </x-select-input>
        <x-input-error class="mt-2" :messages="$errors->get('auction-type')" />
    </div>
    <div>
        <x-input-label for="delivery-type" :value="__('Delivery Type')" />
        <x-select-input id="delivery-type" name="delivery-type" class="mt-1 block w-full" required>
            @foreach ($deliveryTypes as $deliveryType)
                <option value="{{ $deliveryType }}">{{ $deliveryType->name }}</option>
            @endforeach
        </x-select-input>
        <x-input-error class="mt-2" :messages="$errors->get('delivery-type')" />
    </div>
    <div>
        <div class="flex gap-2 align-middle">
            <x-input-label for="price" :value="__('Starting price')" />
            <x-tooltip>
                {{ __('This is the starting bid of the auction, it is recommened to set it to the lowest value you\'d be happy to sell the item for.') }}
            </x-tooltip>
        </div>
        <x-text-input id="price" name="price" type="number" class="mt-1 block w-full" required autocomplete="Starting price" />
        <x-input-error class="mt-2" :messages="$errors->get('price')" />
    </div>
    <div>
        <div class="flex gap-2 align-middle">
            <x-input-label for="end-time" :value="__('End time')" />
            <x-tooltip>
                {{ __('The time when the auction will end.') }}
            </x-tooltip>
        </div>
        
        <x-flatpickr :min-date="today()" :max-date="today()->addMonths(2)" show-time id="end-time" name="end-time" class="mt-1 block font-normal text-md text-gray-700 dark:text-gray-300 dark:bg-gray-900 rounded w-full" required autocomplete="End time"/>
        <x-input-error class="mt-2" :messages="$errors->get('end-time')" />
    </div>
    <div class="flex items-center justify-end mt-4">
        <x-primary-button>
            {{ __('Create') }}
        </x-primary-button>
    </div>
</form>


@include('flatpickr::components.script')

