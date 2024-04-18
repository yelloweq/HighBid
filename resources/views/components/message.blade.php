@props(['message', 'type'])

<div
    id="message"
    hx-get="{{ route('htmx.remove') }}"
    hx-target="this"
    hx-swap="outerHTML swap:1s"
    hx-trigger="load delay:4s"
    class="relative block w-full p-4 mb-4 text-base leading-5 text-white 
    {{ $type == 'error' ? 'bg-red-500' : 'bg-green-500' }} rounded-lg opacity-100 font-regular">
    {{ $message }}
</div>
