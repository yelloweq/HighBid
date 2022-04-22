@if ($watchersCount > 0)
    <div class="text-orange-400 text-center">
        <i class="fa-solid fa-fire-flame-curved animate-pulse"></i><span class="ml-2">
            {{ $watchersCount }}
            @if ($watchersCount > 1)
                {{ __('views in the past 24 hours') }}
            @else
                {{ __('view in the past 24 hours') }}
            @endif
        </span>
    </div>
@endif
