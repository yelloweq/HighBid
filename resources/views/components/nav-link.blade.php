@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-white focus:outline-none focus:border-blue-accent transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-gray-500  hover:text-gray-200 focus:outline-none focus:text-gay-100 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
