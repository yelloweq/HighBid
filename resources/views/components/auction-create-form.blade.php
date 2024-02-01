<form 
    method="POST"
    action="{{ route('auction.create') }}" 
    hx-post="{{ route('auction.create') }}" 
    hx-swap="outerHTML" 
    hx-target="main"
    >
    @csrf
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
        <x-input-label for="auction-type" :value="__('Type of auction')" />
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
        <x-input-label for="price" :value="__('Price')" />
        <x-text-input id="price" name="price" type="number" class="mt-1 block w-full" required autocomplete="Price" />
        <x-input-error class="mt-2" :messages="$errors->get('price')" />
    </div>
    <div>
        <x-input-label for="start-time" :value="__('Start time')" />
        <x-text-input id="start-time" name="start-time" type="date" class="mt-1 block w-full" required autocomplete="Start time" />
        <x-input-error class="mt-2" :messages="$errors->get('start-time')" />
    </div>
    <div>
        <x-input-label for="end-time" :value="__('End time')" />
        <x-text-input id="end-time" name="end-time" type="date" class="mt-1 block w-full" required autocomplete="End time" />
        <x-input-error class="mt-2" :messages="$errors->get('end-time')" />
    </div>
    <div class="flex items-center justify-end mt-4">
        <x-primary-button>
            {{ __('Create') }}
        </x-primary-button>
    </div>
</form>


