@props(['disabled' => false])

@php
$tooltipId = 'tooltip_' . uniqid();
@endphp

<div data-tooltip-target="{{ $tooltipId }}"
class="mt-1 align-middle select-none font-sans font-medium text-center items-center justify-center uppercase transition-all disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none w-6 max-w-[40px] h-6 max-h-[40px] text-xs bg-gray-900 text-white shadow-md shadow-gray-900/10 hover:shadow-lg hover:shadow-gray-900/20 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none rounded-full">
    <i class="fa-solid fa-info pt-1"></i>
</div>
<div data-tooltip="{{ $tooltipId }}"
class="absolute z-50 whitespace-normal break-words rounded-lg bg-black py-1.5 px-3 font-sans text-sm font-normal text-white focus:outline-none max-w-60">
    {{ $slot }}
</div>