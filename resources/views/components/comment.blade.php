@props(['comment'])
@php
    $depthMargin = $comment->depth * 10;
@endphp
<div style="margin-left: {{ $depthMargin }}px" class="{{ $depthMargin > 0 ? 'border-l-2 border-white pl-4' : '' }}">
    <div style="margin-left: {{ $comment->depth * 5 }}px"
        class="comment mt-4 bg-blue-secondary p-4  rounded-md max-w-[600px]">
        <div class="flex items-center gap-3">
            <div class="flex flex-col">
                <div class="mb-2">{{ $comment->user->username }}</div>
                <p class="block font-sans text-sm antialiased font-normal leading-normal text-gray-100">
                    {{ $comment->body }}
                </p>
            </div>
            <x-rating :model="$comment" type="comment" />
        </div>
    </div>
    @if ($comment->replies->count() > 0)
        <div style="margin-left: {{ $depthMargin }}px" class="text-sm text-gray-400 mt-1 show-replies-toggle">
            show replies
        </div>
        @foreach ($comment->replies as $reply)
            <x-comment :comment="$reply" />
        @endforeach
    @endif
</div>
