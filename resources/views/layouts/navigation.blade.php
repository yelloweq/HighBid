<nav x-data="{ open: false }" class="bg-transparent">
    <div class="max-w-11xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-24">
            <div class="flex items-center">
                <a href="{{ route('home') }}">
                    <h1
                        class="text-transparent bg-clip-text font-inter font-semibold text-3xl bg-gradient-to-r from-indigo-300 to-indigo-700">
                        HighBid</h1>
                </a>
            </div>

            <div class="items-center space-x-8 hidden md:flex">
                @auth
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                @endauth
                <x-nav-link :href="route('auctions')" :active="request()->routeIs('auctions')">
                    {{ __('Auctions') }}
                </x-nav-link>
                @auth
                    <x-nav-link :href="route('messages')" :active="request()->routeIs('messages')" hx-boost="false">
                        {{ __('Messages') }}
                    </x-nav-link>
                @endauth
                <x-nav-link :href="route('forum')" :active="request()->routeIs('forum')">
                    {{ __('Forum') }}
                </x-nav-link>
                <x-nav-link :href="route('about')" :active="request()->routeIs('about')">
                    {{ __('About') }}
                </x-nav-link>
                <x-nav-link :href="route('faq')" :active="request()->routeIs('faq')">
                    {{ __('FAQ') }}
                </x-nav-link>
            </div>

            <!-- Settings Dropdown -->
            @auth
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <div class="sm:-my-px sm:ms-10 sm:flex sm:me-4">
                        <x-nav-link-button :href="route('auction.create')" :active="request()->routeIs('auction.create')" hx-boost="false">
                            {{ __('Create Auction') }}
                        </x-nav-link-button>
                    </div>
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-6 py-3 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-300 bg-blue-secondary hover:text-white focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->username }}</div>
                                <svg class="ml-2 h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}"
                                onsubmit="event.preventDefault(); this.submit();">
                                @csrf
                                <x-dropdown-link :href="route('logout')">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            @else
                <div class="flex items-center space-x-4">
                    <x-nav-link-button :href="route('login')">
                        {{ __('Login') }}
                    </x-nav-link-button>
                    <x-nav-link-button :href="route('register')">
                        {{ __('Register') }}
                    </x-nav-link-button>
                </div>
            @endauth

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center md:hidden">
                <button @click="open = !open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-gray-400 hover:bg-gray-900 focus:outline-none focus:bg-gray-900 focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" d="M4 6h16M4 12h16M4 18h16"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" d="M6 18L18 6M6 6l12 12"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden md:hidden">
        @auth
            <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
            </div>
            <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link :href="route('auctions')" :active="request()->routeIs('auctions')">
                    {{ __('Auctions') }}
                </x-responsive-nav-link>
            </div>
            <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link :href="route('auction.create')" :active="request()->routeIs('auction.create')" hx-boost="false">
                    {{ __('Create Auction') }}
                </x-responsive-nav-link>
            </div>

            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-blue-accent">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-200">{{ Auth::user()->username }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>


                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                    this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        </div>
    @endauth
    </div>
</nav>
