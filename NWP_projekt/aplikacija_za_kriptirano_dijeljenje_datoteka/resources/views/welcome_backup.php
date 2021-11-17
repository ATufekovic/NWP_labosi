<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'File site') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>

        <link rel="shortcut icon" href="{{asset("favicon.ico")}}" type="image/x-icon">
    </head>
    <body class="antialiased">
        <div class="relative flex items-top justify-center min-h-screen bg-gray-100 sm:items-center py-4 sm:pt-0">
            @if (Route::has('login'))
                <div class="fixed top-1 right-0 px-6 py-4 sm:block">
                    @auth
                    <form method="POST" action="{{ url(App::getLocale() . '/logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="url(App::getLocale() . '/logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('navigation.logOutText') }}
                        </x-responsive-nav-link>
                    </form>
                    @else
                    <div class="mt-1">
                        <a href="{{ route('login', App::getLocale()) }}" class="text-sm text-gray-700 underline">{{__("welcome.logInLinkText")}}</a>
                        @if (Route::has('register'))
                        <a href="{{ route('register', App::getLocale()) }}" class="ml-4 text-sm text-gray-700 underline">{{__("welcome.registerLinkText")}}</a>
                        @endif
                    </div>                       
                    @endauth
                </div>
                <div class="fixed top-1 right-36 px-6 py-4 sm:block">
                    <div x-data="{ show: false }"  @click.away="show = false">
                        <button @click="show = ! show" type="button" class="flex items-center bg-gray-100 border-gray-200 border-2 px-2 py-2 focus:outline-none focus:border-gray-300 text-sm">
                        <span class="pr-2">{{Config::get('languages')[App::getLocale()]}}</span> 
                            <svg class="fill-current text-gray-200" height="24" viewBox="0 0 24 24" width="24"><path d="M7 10l5 5 5-5z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                        </button>
                        <div x-show="show" class="absolute bg-gray-100 z-10 shadow-md" style="min-width:10rem">
                            @foreach (Config::get("languages") as $lang => $language)
                            @if (App::getLocale() !== $lang)
                            <a class="block px-3 py-2" href="{{ url($lang . "/") }}">
                                {{$language}}
                            </a>
                            <hr class="border-t border-gray-200 my-0">
                            @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
            <div class="grid sm:grid-cols-1 items-center">
                <div class="p-14">
                    <h2 class="font-bold">{{__("welcome.titleText")}}</h2>
                </div>
                <div>
                    @auth
                    <div class="grid sm:grid-cols-2">
                        <a href="{{url(App::getLocale() . "/dashboard")}}" class="bg-gray-200 p-2 mx-2 my-1">{{__("welcome.dashboardLinkText")}}</a>
                        <a href="{{url(App::getLocale() . "/files")}}" class="bg-gray-200 p-2 mx-2 my-1">{{__("welcome.filesLinkText")}}</a>
                    </div>
                    @else
                    <p>{{__("welcome.ifNotLoggedInLoginText")}}<a href="{{route("login", App::getLocale())}}" class="font-mono bg-gray-200">{{__("welcome.ifNotLoggedInClickHereText")}}</a>.</p>
                    <p>{{__("welcome.ifNotLoggedInRegisterText")}}<a href="{{route("register", App::getLocale())}}"class="font-mono bg-gray-200">{{__("welcome.ifNotLoggedInClickHereText")}}</a>.</p>
                    @endauth
                </div>
            </div>
        </div>
    </body>
</html>
