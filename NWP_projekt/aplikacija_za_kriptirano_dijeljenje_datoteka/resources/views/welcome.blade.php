<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight bg-gray-200 rounded-md p-2">
            {{__("welcome.titleText")}}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="bg-gray-200 rounded-md p-2 mb-1">{{__("dashboard.articleTitle")}}</h2>
                    @auth
                    @else
                    <div class="grid gap-1 sm:grid-cols-2">
                        <div class="bg-gray-100 rounded-md p-2">{{__("welcome.ifNotLoggedInLoginText")}} <a href="{{route("login", App::getLocale())}}" class="font-mono bg-gray-200">{{__("welcome.ifNotLoggedInClickHereText")}}</a></div>
                        <div class="bg-gray-100 rounded-md p-2">{{__("welcome.ifNotLoggedInRegisterText")}} <a href="{{route("register", App::getLocale())}}" class="font-mono bg-gray-200">{{__("welcome.ifNotLoggedInClickHereText")}}</a></div>
                    </div>
                    @endauth
                    
                </div>
            </div>
        </div>
        
    </div>
</x-app-layout>
