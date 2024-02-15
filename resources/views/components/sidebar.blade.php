<div
    class="relative flex h-auto w-full max-w-[20rem] flex-col rounded-xl bg-white bg-clip-border p-4 text-gray-700 shadow-xl shadow-blue-gray-900/5">
    <form hx-post={{ route('auction.search') }} hx-target="[hx-auction-grid]" hx-swap="outerHTML"
        hx-trigger="input change from:body delay:500ms"
        class="flex min-w-[240px] flex-col gap-1 p-2 font-sans text-base font-normal text-blue-gray-700">
        @csrf

        <div class="p-2 mb-2">
            <h5
                class="block font-sans text-xl antialiased font-semibold leading-snug tracking-normal text-blue-gray-900">
                Search
            </h5>
            <x-text-input light="true" id="search" name="search" type="text" class="mt-1 block w-full" autocomplete="false" />
        <x-input-error class="mt-2" :messages="$errors->get('search')" />
        </div>

        
        @if(! empty($categories) && count($categories) > 0)
        <div class="p-2 mb-2">
            <h5
                class="block font-sans text-xl antialiased font-semibold leading-snug tracking-normal text-blue-gray-900">
                Categories
            </h5>

            <div class="relative flex flex-col text-primary bg-white rounded-xl bg-clip-border">

                <div role="button"
                    class="flex items-center w-full p-0 leading-tight transition-all rounded-lg outline-none text-start hover:bg-blue-gray-50 hover:bg-opacity-80 hover:text-blue-gray-900 focus:bg-blue-gray-50 focus:bg-opacity-80 focus:text-blue-gray-900 active:bg-blue-gray-50 active:bg-opacity-80 active:text-blue-gray-900">
                    <label htmlFor="category" class="flex items-center w-full px-3 py-2 cursor-pointer">
                        <div class="grid mr-3 place-items-center">
                            <div class="inline-flex items-center">
                                <label class="relative flex items-center p-0 rounded-full cursor-pointer"
                                    htmlFor="category">
                                    <input name="category" id="category" type="radio" value=""
                                        class="before:content[''] peer relative h-5 w-5 cursor-pointer appearance-none rounded-full border border-blue-gray-200 text-gray-900 transition-all before:absolute before:top-2/4 before:left-2/4 before:block before:h-12 before:w-12 before:-translate-y-2/4 before:-translate-x-2/4 before:rounded-full before:bg-blue-gray-500 before:opacity-0 before:transition-opacity checked:border-gray-900 checked:before:bg-gray-900 hover:before:opacity-0" />
                                    <span
                                        class="absolute text-primary transition-opacity opacity-0 pointer-events-none top-2/4 left-2/4 -translate-y-2/4 -translate-x-2/4 peer-checked:opacity-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 16 16"
                                            fill="currentColor">
                                            <circle data-name="ellipse" cx="8" cy="8" r="8"></circle>
                                        </svg>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <p class="block font-sans text-base antialiased font-medium leading-relaxed text-primary">
                            All
                        </p>
                    </label>
                </div>
                @foreach ($categories as $category)
                    <div role="button"
                        class="flex items-center w-full p-0 leading-tight transition-all rounded-lg outline-none text-start hover:bg-blue-gray-50 hover:bg-opacity-80 hover:text-blue-gray-900 focus:bg-blue-gray-50 focus:bg-opacity-80 focus:text-blue-gray-900 active:bg-blue-gray-50 active:bg-opacity-80 active:text-blue-gray-900">
                        <label htmlFor="category" class="flex items-center w-full px-3 py-2 cursor-pointer">
                            <div class="grid mr-3 place-items-center">
                                <div class="inline-flex items-center">
                                    <label class="relative flex items-center p-0 rounded-full cursor-pointer"
                                        htmlFor="category">
                                        <input name="category" id="category" type="radio" value="{{ $category->id }}"
                                            class="before:content[''] peer relative h-5 w-5 cursor-pointer appearance-none rounded-full border border-blue-gray-200 text-gray-900 transition-all before:absolute before:top-2/4 before:left-2/4 before:block before:h-12 before:w-12 before:-translate-y-2/4 before:-translate-x-2/4 before:rounded-full before:bg-blue-gray-500 before:opacity-0 before:transition-opacity checked:border-gray-900 checked:before:bg-gray-900 hover:before:opacity-0" />
                                        <span
                                            class="absolute text-gray-900 transition-opacity opacity-0 pointer-events-none top-2/4 left-2/4 -translate-y-2/4 -translate-x-2/4 peer-checked:opacity-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5"
                                                viewBox="0 0 16 16" fill="currentColor">
                                                <circle data-name="ellipse" cx="8" cy="8" r="8">
                                                </circle>
                                            </svg>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <p
                                class="block font-sans text-base antialiased font-medium leading-relaxed text-primary">
                                {{ $category->name }}
                            </p>
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
        @endif



        @if(! empty($deliveryTypes) && count($deliveryTypes) > 0)
        <div class="p-2 mb-4">
            <h5
                class="block font-sans text-xl antialiased font-semibold leading-snug tracking-normal text-blue-gray-900">
                Delivery Type
            </h5>

            @foreach ($deliveryTypes as $deliveryType)
                <div role="button"
                    class="flex items-center w-full p-0 leading-tight transition-all rounded-lg outline-none text-start hover:bg-blue-gray-50 hover:bg-opacity-80 hover:text-blue-gray-900 focus:bg-blue-gray-50 focus:bg-opacity-80 focus:text-blue-gray-900 active:bg-blue-gray-50 active:bg-opacity-80 active:text-blue-gray-900">
                    <label htmlFor="delivery" class="flex items-center w-full px-3 py-2 cursor-pointer">
                        <div class="grid mr-3 place-items-center">
                            <div class="inline-flex items-center">
                                <label class="relative flex items-center p-0 rounded-full cursor-pointer"
                                    htmlFor="delivery">
                                    <input name="delivery" id="delivery" type="radio"
                                        value="{{ $deliveryType->value }}"
                                        class="before:content[''] peer relative h-5 w-5 cursor-pointer appearance-none rounded-full border border-blue-gray-200 text-gray-900 transition-all before:absolute before:top-2/4 before:left-2/4 before:block before:h-12 before:w-12 before:-translate-y-2/4 before:-translate-x-2/4 before:rounded-full before:bg-blue-gray-500 before:opacity-0 before:transition-opacity checked:border-gray-900 checked:before:bg-gray-900 hover:before:opacity-0" />
                                    <span
                                        class="absolute text-gray-900 transition-opacity opacity-0 pointer-events-none top-2/4 left-2/4 -translate-y-2/4 -translate-x-2/4 peer-checked:opacity-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 16 16"
                                            fill="currentColor">
                                            <circle data-name="ellipse" cx="8" cy="8" r="8">
                                            </circle>
                                        </svg>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <p class="block font-sans text-base antialiased font-medium leading-relaxed text-primary">
                            {{ ucfirst($deliveryType->value) }}
                        </p>
                    </label>
                </div>
            @endforeach
        </div>
        @endif
    </form>
</div>
