@props(['comments' => null])

@if ($comments)
    <section id="comment-section">
        @foreach ($comments as $comment)
            @if ($comment->parent_id === null)
                <x-comment :comment="$comment" />
            @endif
        @endforeach
    </section>
@endif
