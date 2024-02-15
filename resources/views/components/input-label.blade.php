@props(['value', 'light' => false])

@if ($light)
    <label {{ $attributes->merge(['class' => 'block font-medium text-lg text-bg-primary']) }}>
        {{ $value ?? $slot }}
    </label>
    @else
    <label {{ $attributes->merge(['class' => 'block font-medium text-lg text-gray-300']) }}>
        {{ $value ?? $slot }}
    </label>
@endif
