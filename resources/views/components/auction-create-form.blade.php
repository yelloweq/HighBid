<form 
    method="POST"
    action="{{ route('auction.create') }}" 
    hx-post="{{ route('auction.create') }}" 
    hx-swap="outerHTML" 
    hx-target="this"
    hx-on="htmx:afterRequest: if(event.detail.successful) this.reset();"
    >
    @csrf
    <label for="title">{{ __('Title') }}</label>
    <input type="text" id="title" name="title" class="dark:white dark:bg-gray-800" />
    <div id="title-error">{{ $errors->first('title') }}</div>

    <label for="description">{{ __('Description') }}</label>
    <input type="text" id="description" name="description" class="dark:white dark:bg-gray-800" />
    <div id="description-error">{{ $errors->first('description') }}</div>

    <label for="features">{{ __('Features') }}</label>
    <textarea id="features" name="features" class="dark:white dark:bg-gray-800"></textarea>
    <div id="features-error">{{ $errors->first('features') }}</div>

    <label for="auction-type">{{ __('Auction type') }}</label>
    <select name="auction-type" id="auction-type" class="dark:white dark:bg-gray-800">
        @foreach ($auctionTypes as $auctionType)
            <option value="{{ $auctionType }}">{{ $auctionType->name }}</option>
        @endforeach
    </select>
    <div id="auction-type-error">{{ $errors->first('auction-type') }}</div>

    <label for="delivery-type">{{ __('Delivery type') }}</label>
    <select name="delivery-type" id="delivery-type" class="dark:white dark:bg-gray-800">
        @foreach ($deliveryTypes as $deliveryType)
            <option value="{{ $deliveryType }}">{{ $deliveryType->name }}</option>
        @endforeach
    </select>
    <div id="delivery-type-error">{{ $errors->first('delivery-type') }}</div>

    <label for="price">{{ __('Price') }}</label>
    <input type="number" id="price" name="price" class="dark:white dark:bg-gray-800" />
    <div id="price-error">{{ $errors->first('price') }}</div>

    <label for="start_time">{{ __('Start time') }}</label>
    <input type="date" id="start-time" name="start-time"
        class="dark:white dark:bg-gray-800" />
    <div id="start-time-error">{{ $errors->first('start-time') }}</div>

    <label for="end_time">{{ __('End time') }}</label>
    <input type="date" id="end-time" name="end-time"
        class="dark:white dark:bg-gray-800" />
        <div id="end-time-error">{{ $errors->first('end-time') }}</div>

    <button type="submit">{{ __('Create') }}</button>
</form>