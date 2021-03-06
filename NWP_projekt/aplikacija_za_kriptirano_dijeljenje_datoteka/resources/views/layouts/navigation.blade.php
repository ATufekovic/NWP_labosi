<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    @auth
                    <a href="{{ url(App::getLocale() . '/dashboard') }}">
                        <x-application-logo class="block h-10 w-auto fill-current text-gray-600" />
                    </a>
                    @else
                    <a href="{{ url(App::getLocale() . '/') }}">
                        <x-application-logo class="block h-10 w-auto fill-current text-gray-600" />
                    </a>
                    @endauth
                    
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    @auth
                    <x-nav-link :href="url(App::getLocale() . '/dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('navigation.dashboardLinkText') }}
                    </x-nav-link>
                    <x-nav-link :href="url(App::getLocale() . '/files')" :active="request()->routeIs('files')">
                        {{ __('navigation.filesLinkText') }}
                    </x-nav-link>
                    <x-nav-link>
                        <div class="pt-2 relative mx-auto text-gray-600">
                            <form action="{{url(App::getLocale() . "/viewUserFiles")}}" method="get">
                                <input class="border-2 bg-gray-100 border-gray-200 h-10 px-5 pr-16 text-sm focus:outline-none focus:border-gray-300"
                                type="username" name="username" placeholder="Username">
                                <button type="submit" class="absolute right-0 top-0 mt-5 mr-4">
                                    <svg class="text-gray-600 h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px"
                                        viewBox="0 0 56.966 56.966" style="enable-background:new 0 0 56.966 56.966;" xml:space="preserve"
                                        width="512px" height="512px">
                                        <path
                                        d="M55.146,51.887L41.588,37.786c3.486-4.144,5.396-9.358,5.396-14.786c0-12.682-10.318-23-23-23s-23,10.318-23,23  s10.318,23,23,23c4.761,0,9.298-1.436,13.177-4.162l13.661,14.208c0.571,0.593,1.339,0.92,2.162,0.92  c0.779,0,1.518-0.297,2.079-0.837C56.255,54.982,56.293,53.08,55.146,51.887z M23.984,6c9.374,0,17,7.626,17,17s-7.626,17-17,17  s-17-7.626-17-17S14.61,6,23.984,6z" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </x-nav-link>
                    @else
                    
                    @endauth
                    
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <div class="mr-2">
                    <div x-data="{ show: false }"  @click.away="show = false">
                        <button @click="show = ! show" type="button" class="flex items-center bg-gray-100 border-gray-200 border-2 px-2 py-2 focus:outline-none focus:border-gray-300 text-sm">
                        <span class="pr-2">{{Config::get('languages')[App::getLocale()]}}</span> 
                            <svg class="fill-current text-gray-200" height="24" viewBox="0 0 24 24" width="24"><path d="M7 10l5 5 5-5z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                        </button>
                        <div x-show="show" class="absolute bg-gray-100 order-gray-200 border-2 z-10 shadow-md text-sm" style="min-width:10rem">
                            @foreach (Config::get("languages") as $lang => $language)
                            @if (App::getLocale() !== $lang)
                            @auth
                            <a class="block px-3 py-2 hover:bg-gray-200" href="{{ url($lang . "/dashboard") }}">
                                {{$language}}
                            </a>
                            @else
                            <a class="block px-3 py-2 hover:bg-gray-200" href="{{ url($lang . "/") }}">
                                {{$language}}
                            </a>
                            @endauth
                            
                            <hr class="border-t border-gray-200 my-0">
                            @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                @auth
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button style="height: 24px" class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('navigation.logOutText') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
                @else
                <x-nav-link :href="route('login', App::getLocale())">
                    {{ __('welcome.logInLinkText') }}
                </x-nav-link>
                <x-nav-link :href="route('register', App::getLocale())">
                    {{ __('welcome.registerLinkText') }}
                </x-nav-link>
                @endauth
                
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @auth
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('navigation.dashboardLinkText') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('files')" :active="request()->routeIs('files')">
                {{ __('navigation.filesLinkText') }}
            </x-responsive-nav-link>
            <x-nav-link>
                <div class="pt-2 relative mx-auto text-gray-600">
                    <form action="{{url(App::getLocale() . "/viewUserFiles")}}" method="get">
                        <input class="border-2 bg-gray-100 border-gray-200 h-10 px-5 pr-max rounded-lg text-sm focus:outline-none focus:border-gray-300"
                        type="username" name="username" placeholder="Username">
                        <button type="submit" class="absolute right-0 top-0 mt-5 mr-4">
                            <svg class="text-gray-600 h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px"
                                viewBox="0 0 56.966 56.966" style="enable-background:new 0 0 56.966 56.966;" xml:space="preserve"
                                width="512px" height="512px">
                                <path
                                d="M55.146,51.887L41.588,37.786c3.486-4.144,5.396-9.358,5.396-14.786c0-12.682-10.318-23-23-23s-23,10.318-23,23  s10.318,23,23,23c4.761,0,9.298-1.436,13.177-4.162l13.661,14.208c0.571,0.593,1.339,0.92,2.162,0.92  c0.779,0,1.518-0.297,2.079-0.837C56.255,54.982,56.293,53.08,55.146,51.887z M23.984,6c9.374,0,17,7.626,17,17s-7.626,17-17,17  s-17-7.626-17-17S14.61,6,23.984,6z" />
                            </svg>
                        </button>
                    </form>
                </div>
            </x-nav-link>
            @else
            <x-responsive-nav-link :href="route('login', App::getLocale())">
                {{ __('welcome.logInLinkText') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('register', App::getLocale())">
                {{ __('welcome.registerLinkText') }}
            </x-responsive-nav-link>
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        @auth
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>
            <div class="mt-3 space-y-1">
                <!-- Authentication -->
                <form method="POST" action="{{ url(App::getLocale() . '/logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="url(App::getLocale() . '/logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('navigation.logOutText') }}
                    </x-responsive-nav-link>
                </form>
            </div>
            
        </div>
        @endauth
        <div class="pt-2 pb-1 border-t border-gray-200">
            <div class="mx-2">
                <div x-data="{ show: false }"  @click.away="show = false">
                    <button @click="show = ! show" type="button" class="flex items-center bg-gray-100 border-gray-200 border-2 px-2 py-2 focus:outline-none focus:border-gray-300 text-sm">
                    <span class="pr-2">{{Config::get('languages')[App::getLocale()]}}</span> 
                        <svg class="fill-current text-gray-200" height="24" viewBox="0 0 24 24" width="24"><path d="M7 10l5 5 5-5z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                    </button>
                    <div x-show="show" class="absolute bg-gray-100 z-10 shadow-md" style="min-width:10rem">
                        @foreach (Config::get("languages") as $lang => $language)
                        @if (App::getLocale() !== $lang)
                        @auth
                        <a class="block px-3 py-2 hover:bg-gray-200" href="{{ url($lang . "/dashboard") }}">
                            {{$language}}
                        </a>
                        @else
                        <a class="block px-3 py-2 hover:bg-gray-200" href="{{ url($lang . "/") }}">
                            {{$language}}
                        </a>
                        @endauth
                        <hr class="border-t border-gray-200 my-0">
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
