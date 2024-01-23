@php
    use Illuminate\Support\Collection;
@endphp

@foreach ($errors->keys() as $key)
    <div class="text-red-400" id="{{$key}}-error" hx-swap-oob="true">{{ $errors->first($key) }}</div>
@endforeach

@php
    $excludedErrors = ['title', 'description', 'features', 'price', 'auction-type', 'delivery-type', 'start-time', 'end-time'];
    $other_errors = collect($errors->getMessages())->except($excludedErrors);
@endphp

@if (!$other_errors->isEmpty())
    @foreach ($other_errors->flatten() as $message)
        <div class="text-red-400">{{ $message }}</div>
    @endforeach
@endif
