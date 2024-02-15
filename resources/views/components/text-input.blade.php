@props(['disabled' => false, 'light' => false])

@if (!$light)
<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-700 bg-gray-900 text-gray-300 focus:border-indigo-600 focus:ring-indigo-600 rounded-md shadow-sm ']) !!}>
@else
<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-400 bg-white text-bg-primary focus:border-indigo-600 focus:ring-indigo-600 rounded-md shadow-sm ']) !!}>
@endif