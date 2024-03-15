@props(['active'])

@php
$classes = ($active ?? false)
            ? 'hidden inline-flex items-center text-sm font-medium leading-5 text-gray-500 bg-blue-accent text-white rounded py-3 px-6 items-center hover:text-gray-200 focus:outline-none focus:text-gay-100 transition duration-150 ease-in-out'
            : 'inline-flex items-center text-sm font-medium leading-5 text-gray-500 bg-blue-accent text-white rounded py-3 px-6 items-center hover:text-gray-200 focus:outline-none focus:text-gay-100 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
