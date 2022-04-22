@props(['thread'])
<x-app-layout>
    <section class="mx-auto max-w-7xl text-white " id="hx-forum-content">
        <div
            class="relative flex flex-col w-full min-h-[600px]  text-white bg-gray-900 shadow-md rounded-xl bg-clip-border">
            <div class="flex h-max min-h-[400px]">
                <div class="mx-4 mt-4 overflow-hidden text-white bg-gray-900 rounded-none bg-clip-border h-full w-full">
                    <div class="flex flex-col items-center justify-center gap-8 mb-8">
                        <div>
                            <h5
                                class="font-sans text-xl antialiased font-semibold leading-snug tracking-normal text-gray-100">
                                {{ $thread?->title }}
                            </h5>
                        </div>
                        <p>
                            {{ $thread?->body }}
                        </p>
                    </div>
                </div>
                <x-rating :model="$thread" type="thread" />
            </div>
        </div>
        <div hx-get="{{ route('thread.comments', $thread) }}" hx-swap="outerHTML" hx-target="this" hx-trigger="load">
        </div>
    </section>
</x-app-layout>
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log("DOM fully loaded and parsed")
            const showRepliesToggles = document.querySelectorAll('.show-replies-toggle');

            showRepliesToggles.forEach(toggle => {
                toggle.addEventListener('click', function() {
                    this.nextElementSibling.classList.toggle('hidden');
                });
            });
        });
    </script>
@endpush
