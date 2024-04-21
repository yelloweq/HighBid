@push('styles')
    <style>
        #message.htmx-added {
            opacity: 0;
        }

        #message.htmx-swapping {
            opacity: 0;
            transition: opacity 500ms ease-out;
        }

        #message {
            opacity: 1;
            transition: opacity 500ms ease-in;
        }

        .messages:has(> #message) {
            display: block;
            min-height: 3.5rem;
            transition: min-height 500ms ease-in-out;
        }

        .messages {
            min-height: 0rem;
        }
    </style>
@endpush
@props(['thread' => null])
<x-app-layout>
    <section class="mx-auto max-w-7xl text-white" id="hx-forum-content">
        <div class="relative flex flex-col w-full h-full text-white bg-gray-900 shadow-md rounded-xl bg-clip-border">
            <div class="relative mx-4 mt-4 overflow-hidden text-white bg-gray-900 rounded-none bg-clip-border">
                <div class="flex flex-col items-center justify-center gap-8 mb-8">
                    <div>
                        <h5
                            class="font-sans text-xl antialiased font-semibold leading-snug tracking-normal text-gray-100">
                            {{ $thread ? __('Edit your thread') : __('Create a new thread') }}
                        </h5>
                    </div>
                    <form class="grid grid-cols-3 gap-3 h-full w-full p-4" hx-post="{{ route('thread.store') }}"
                        hx-target="#messages">
                        @csrf
                        <div class="mb-4 col-span-2">
                            <x-text-input-with-label type="text" label="title" class="mt-1 block w-full" required />
                        </div>
                        <div class="mb-4">
                            <x-select name="tag" id="tag" title="Tags">
                            </x-select>
                            <div hx-get="{{ route('thread.tags') }}" hx-swap="innerHTML"
                                hx-target="previous .dropdown-select" hx-trigger="load">
                            </div>
                        </div>
                        <div class="mb-4 col-span-3">
                            <div class="relative w-full min-w-[200px]">
                                <textarea name="body" id="body"
                                    class="peer h-full min-h-[100px] w-full resize-none rounded-[7px] border border-blue-gray-200 border-t-transparent bg-transparent px-3 py-2.5 font-sans text-sm font-normal text-blue-gray-200 outline outline-0 transition-all placeholder-shown:border placeholder-shown:border-blue-gray-200 placeholder-shown:border-t-blue-gray-200 focus:border-2 focus:border-gray-900 focus:border-t-transparent focus:outline-0 disabled:resize-none disabled:border-0 disabled:bg-blue-gray-50"
                                    placeholder=" "></textarea>
                                <label for="body"
                                    class="before:content[' '] after:content[' '] pointer-events-none absolute left-0 -top-1.5 flex h-full w-full select-none text-[11px] font-normal leading-tight text-blue-gray-400 transition-all before:pointer-events-none before:mt-[6.5px] before:mr-1 before:box-border before:block before:h-1.5 before:w-2.5 before:rounded-tl-md before:border-t before:border-l before:border-blue-gray-200 before:transition-all after:pointer-events-none after:mt-[6.5px] after:ml-1 after:box-border after:block after:h-1.5 after:w-2.5 after:flex-grow after:rounded-tr-md after:border-t after:border-r after:border-blue-gray-200 after:transition-all peer-placeholder-shown:text-sm peer-placeholder-shown:leading-[3.75] peer-placeholder-shown:text-blue-gray-500 peer-placeholder-shown:before:border-transparent peer-placeholder-shown:after:border-transparent peer-focus:text-[11px] peer-focus:leading-tight peer-focus:text-gray-300 peer-focus:before:border-t-2 peer-focus:before:border-l-2 peer-focus:before:border-gray-900 peer-focus:after:border-t-2 peer-focus:after:border-r-2 peer-focus:after:border-gray-900 peer-disabled:text-transparent peer-disabled:before:border-transparent peer-disabled:after:border-transparent peer-disabled:peer-placeholder-shown:text-blue-gray-500">
                                    Content
                                </label>
                            </div>
                            <div class="messages" id="messages">
                            </div>
                            <x-primary-button class="mt-4">
                                {{ $thread ? __('Submit') : __('Create') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
