@props(['threads' => []])
<tbody id="forum-body">
    @foreach ($threads as $thread)
        <tr class=" cursor-pointer">
            <td class="p-4 border-b border-blue-gray-50">
                <div class="flex items-center gap-3">
                    <div class="flex flex-col">
                        <p class="block font-sans text-sm antialiased font-normal leading-normal text-gray-100">
                            {{ $thread->title }}
                        </p>
                        <p
                            class="block font-sans text-sm antialiased font-normal leading-normal text-gray-100 opacity-70">
                            {{ substr($thread->body, 0, 40) . (strlen($thread->body) > 40 ? '...' : '') }}
                        </p>
                    </div>
                </div>
            </td>
            <td class="p-4 border-b border-blue-gray-50">
                <div class="flex items-center gap-3">
                    <div class="flex flex-col">
                        <img src="https://demos.creative-tim.com/test/corporate-ui-dashboard/assets/img/team-3.jpg"
                            alt="John Michael"
                            class="relative inline-block h-9 w-9 !rounded-full object-cover object-center" />
                    </div>
                    <p class="font-sans text-sm antialiased font-normal leading-normal text-gray-100">
                        {{ $thread->user->username }}
                    </p>
                </div>
            </td>
            <td class="p-4 border-b border-blue-gray-50">
                <div class="w-max">
                    @foreach ($thread->tags as $tag)
                        <div
                            class="relative grid items-center mb-2 px-2 py-1 font-sans text-xs font-bold text-green-200 uppercase rounded-md select-none whitespace-nowrap bg-green-500/50">
                            <span class="">{{ $tag->tag->name }}</span>
                        </div>
                    @endforeach
                </div>
            </td>
            <td class="p-4 border-b border-blue-gray-50">
                <p class="block font-sans text-sm antialiased font-normal leading-normal text-gray-100">
                    {{ $thread->created_at }}
                </p>
            </td>
            <td class="p-4 border-b border-blue-gray-50">
                @if (Auth::check() && Auth::user()->id === $thread->user_id)
                    <button hx-get="{{ route('thread.edit', $thread) }}" hx-swap="outerHTML"
                        hx-target="#hx-forum-content"
                        class="relative h-10 max-h-[40px] w-10 max-w-[40px] select-none rounded-lg text-center align-middle font-sans text-xs font-medium uppercase text-gray-200 transition-all hover:bg-gray-900/10 active:bg-gray-900/20 disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                        type="button">
                        <span class="absolute transform -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                aria-hidden="true" class="w-4 h-4">
                                <path
                                    d="M21.731 2.269a2.625 2.625 0 00-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 000-3.712zM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 00-1.32 2.214l-.8 2.685a.75.75 0 00.933.933l2.685-.8a5.25 5.25 0 002.214-1.32L19.513 8.2z">
                                </path>
                            </svg>
                        </span>
                    </button>
                @endif
            </td>
        </tr>
    @endforeach
</tbody>
