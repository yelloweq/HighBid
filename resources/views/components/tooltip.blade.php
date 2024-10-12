@props(['disabled' => false])

@push('styles')
    <style>
        #tooltip-message {
            visibility: hidden;
            opacity: 0;
            font-size: 0;
            transition: opacity 0.4s ease-out;
        }
        #tooltip:hover {
            i  {
                display: none;
            }
            #tooltip-message {
                visibility: visible;
                font-size: .7rem;
                opacity: 1;
            }
        }
    </style>
@endpush

<div id="tooltip"
class="select-none font-inter font-medium bg-blue-accent text-white rounded-full px-2 text-xs h-5">
    <i class="fa-solid fa-info"></i>
    <div id="tooltip-message"
        class="absolute z-50 whitespace-normal break-words rounded-lg bg-black py-1.5 px-3 font-inter text-sm font-normal text-white focus:outline-none">
        {{ $slot }}
    </div>
</div>

