<div class="overflow-hidden">
    <div class="block w-full py-1 font-inter text-sm antialiased font-light leading-normal text-gray-700">
      <nav class="flex min-w-[240px] flex-col gap-1 p-0 font-inter text-base font-normal text-blue-gray-700">
        <div role="button"
        class="flex items-center w-full p-3 leading-tight transition-all rounded-lg outline-none text-start hover:bg-blue-gray-50 hover:bg-opacity-80 hover:text-blue-gray-900 focus:bg-blue-gray-50 focus:bg-opacity-80 focus:text-blue-gray-900 active:bg-blue-gray-50 active:bg-opacity-80 active:text-blue-gray-900">
        <div class="grid mr-4 place-items-center">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3"
            stroke="currentColor" aria-hidden="true" class="w-5 h-3">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"></path>
          </svg>
        </div>
        {{ $subcategory->name }}
      </div>
      </nav>
    </div>
  </div>
</div>