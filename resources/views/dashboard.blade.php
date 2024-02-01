<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div id="content" class="py-12">
        <div class="max-w-7xl">
            <div class="bg-white dark:bg-gray-800">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>
            </div>

            <div class="my-6 text-gray-900 dark:text-gray-100">
                {{ __("Your auctions") }}
            </div>

            
            <div class="bg-white dark:bg-gray-800 flex">
                <x-auction-grid :auctions=$auctions/>
                <div class=" w-full bg-red-500 text-center">
                    <div>Categories</div>
                    <div>...list of categories (links)</div>
                    <div>Price</div>
                    <div>Condition</div>
                    <ul>
                        <li class="sidebar_main_list">
                            <div class="sidebar_item">
                                <h3>
                                    <div>filter name
                                        <div class="sidebar_toggle_arrow"></div>
                                    </div>
                                </h3>
                            </div>
                            <div class="sidebar_item_body">
                                <ul>
                                    <li>
                                        <div>
                                            <a href="#">
                                                <div>
                                                    <span class=" form-checkbox"></span>
                                                    <span>filter option</span>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>         
            </div>
        </div>
        
    </div>
</x-app-layout>
