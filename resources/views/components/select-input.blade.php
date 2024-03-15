@props(['disabled' => false])

<select {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-400 bg-gray-800 text-white focus:border-blue-accent focus:ring-blue-accent rounded-md shadow-sm']) !!}>
    {{ $slot }}
</select>
