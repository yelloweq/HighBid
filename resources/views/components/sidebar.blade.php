
<div
    class="flex h-auto w-full min-w-[150px] flex-col rounded-xl  bg-clip-border  text-white shadow-xl shadow-blue-gray-900/5 sticky top-0">
    <form id="sidebar-form" hx-post="{{ route('auction.search') }}" hx-target="[hx-auction-grid]" hx-swap="outerHTML"
          hx-trigger="input, change from:body delay:500ms" hx-push-url="true"
          hx-on:after-request="window.scrollTo({top: 0, behavior: 'smooth'})"
          class="flex flex-col gap-1 pr-2 font-inter text-base font-normal">
        @csrf
        <div class="p-2 mb-2">
            <h5
                class="block font-inter text-xl antialiased font-semibold leading-snug tracking-normal ">
                Search
            </h5>
            <x-text-input id="search" name="search" type="text" value="" class="mt-1 block w-full text-black"
                          autocomplete="false" :value="old('search', request('search'))" tabindex="0"/>
            <x-input-error class="mt-2" :messages="$errors->get('search')"/>
        </div>


        @if(! empty($categories) && count($categories) > 0)
            <div class="p-2 mb-2">
                <h5
                    class="block font-inter text-xl antialiased font-semibold leading-snug tracking-normal ">
                    Categories
                </h5>

                <div class="relative flex flex-col text-primary  rounded-xl bg-clip-border">

                    <div role="button"
                         class="flex items-center w-full p-0 leading-tight transition-all rounded-lg outline-none text-start hover:bg-blue-gray-50 hover:bg-opacity-80 hover:text-blue-gray-900 focus:bg-blue-gray-50 focus:bg-opacity-80 focus:text-blue-gray-900 active:bg-blue-gray-50 active:bg-opacity-80 active:text-blue-gray-900">
                        <label htmlFor="category" class="flex items-center w-full px-3 py-2 cursor-pointer">
                            <div class="grid mr-3 place-items-center">
                                <div class="inline-flex items-center">
                                    <label class="relative flex items-center p-0 rounded-full cursor-pointer"
                                           htmlFor="category">
                                        <input name="category" id="category" type="radio" value="all"
                                               {{ strtolower(request('category')) == "all" ? 'checked' : '' }}
                                               class="before:content[''] peer relative h-5 w-5 cursor-pointer appearance-none rounded-full border  text-blue-accent transition-all before:absolute before:top-2/4 before:left-2/4 before:block before:h-12 before:w-12 before:-translate-y-2/4 before:-translate-x-2/4 before:rounded-full before:opacity-0 before:transition-opacity checked:border-blue-thirtiary checked:before:bg-gray-900 hover:before:opacity-0"/>
                                        <span
                                            class="absolute text-blue-accent transition-opacity opacity-0 pointer-events-none top-2/4 left-2/4 -translate-y-2/4 -translate-x-2/4 peer-checked:opacity-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5"
                                         viewBox="0 0 16 16" fill="currentColor">
                                        <circle data-name="ellipse" cx="8" cy="8" r="8">
                                        </circle>
                                    </svg>
                                </span>
                                    </label>
                                </div>
                            </div>
                            <p class="block font-inter text-base antialiased font-medium leading-relaxed">
                                All
                            </p>
                        </label>
                    </div>
                    @foreach ($categories as $category)
                        <div role="button" tabindex="0"
                             class="flex items-center w-full p-0 leading-tight transition-all rounded-lg outline-none text-start hover:bg-blue-gray-50 hover:bg-opacity-80 hover:text-blue-gray-900 focus:bg-blue-gray-50 focus:bg-opacity-80 focus:text-blue-gray-900 active:bg-blue-gray-50 active:bg-opacity-80 active:text-blue-gray-900">
                            <label htmlFor="category" class="flex items-center w-full px-3 py-2 cursor-pointer">
                                <div class="grid mr-3 place-items-center">
                                    <div class="inline-flex items-center">
                                        <label class="relative flex items-center p-0 rounded-full cursor-pointer"
                                               htmlFor="category">
                                            <input name="category" id="category" type="radio"
                                                   value="{{ $category->id }}"
                                                   {{ request('category') == $category->id ? 'checked' : '' }}
                                                   class="before:content[''] peer relative h-5 w-5 cursor-pointer appearance-none rounded-full border  text-blue-accent transition-all before:absolute before:top-2/4 before:left-2/4 before:block before:h-12 before:w-12 before:-translate-y-2/4 before:-translate-x-2/4 before:rounded-full before:opacity-0 before:transition-opacity checked:border-blue-thirtiary checked:before:bg-gray-900 hover:before:opacity-0"/>
                                            <span
                                                class="absolute text-blue-accent transition-opacity opacity-0 pointer-events-none top-2/4 left-2/4 -translate-y-2/4 -translate-x-2/4 peer-checked:opacity-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5"
                                                 viewBox="0 0 16 16" fill="currentColor">
                                                <circle data-name="ellipse" cx="8" cy="8" r="8"></circle>
                                            </svg>
                                        </span>
                                        </label>
                                    </div>
                                </div>
                                <p
                                    class="block font-inter text-base antialiased font-medium leading-relaxed text-primary">
                                    {{ ucfirst($category->name) }}
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
                    class="block font-inter text-xl antialiased font-semibold leading-snug tracking-normal">
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
                                               {{ request('delivery') == $deliveryType->value ? 'checked' : '' }}
                                               class="before:content[''] peer relative h-5 w-5 cursor-pointer appearance-none rounded-full border border-blue-gray-200 text-gray-900 transition-all before:absolute before:top-2/4 before:left-2/4 before:block before:h-12 before:w-12 before:-translate-y-2/4 before:-translate-x-2/4 before:rounded-full before:bg-blue-gray-500 before:opacity-0 before:transition-opacity checked:border-gray-900 checked:before:bg-gray-900 hover:before:opacity-0"/>
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
                            <p class="block font-inter text-base antialiased font-medium leading-relaxed text-primary">
                                {{ ucfirst($deliveryType->value) }}
                            </p>
                        </label>
                    </div>
                @endforeach
            </div>
        @endif
    </form>
</div>

@push('scripts')
<script>
    let val = document.getElementById("sidebar-form");
    val.onkeypress = function (key) {
        let btn = 0 || key.keyCode || key.charCode;
        if (btn === 13) {
            key.preventDefault();
        }
    }
</script>
@endpush
