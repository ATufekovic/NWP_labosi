<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight bg-gray-200 rounded-md p-2">
            {{__("files.headerTitle")}}
        </h2>
    </x-slot>
    <div class="py-12">
        @if ($exists === "yes")
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="bg-gray-200 rounded-md p-2">{{__("viewUserFiles.userNameText") . " " . $user->name}}</h2>
                    <div class="grid sm:grid-cols-3">
                        <div class="bg-gray-100 rounded-md p-2 my-1"><p>{{__("viewUserFiles.userCreatedText")}}</p><p>{{$user->created_at}}</p></div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if ($exists === "no")
                    <div>
                    {{__("viewUserFiles.noSuchUserText")}}
                    </div>
                    @elseif ($files->isEmpty())
                    {{__("viewUserFiles.userHasNoPublicFiles")}}
                    @else

                    <div class="grid sm:grid-cols-3 lg:grid-cols-5">
                        @foreach ($files as $file)
                        
                        <div class="bg-gray-100 p-2 border-t-2 border-l-2">
                            <a href="{{url(App::getLocale() . "/viewFile/" . $file->id)}}" target="_blank">
                                <p class="truncate"><span class="font-mono text-sm font-light">{{__("files.itemInfoName")}}</span> {{$file->filename}}</p>
                                <p class="truncate"><span class="font-mono text-sm font-light">{{__("files.itemInfoExtension")}}</span> {{$file->extension}}</p>
                                <p class="truncate"><span class="font-mono text-sm font-light">{{__("files.itemInfoMIME")}}</span> {{$file->MIME}}</p>
                                <p class="truncate"><span class="font-mono text-sm font-light">{{__("files.itemInfoEncryption")}}</span> {{$file->cipher}}</p>
                                <p class="truncate"><span class="font-mono text-sm font-light">{{__("files.itemInfoSize")}}</span>
                                @php
                                if($file->size < 1000){
                                    echo $file->size . ' B';
                                } elseif ($file->size >= 1000 && $file->size < 1000000) {
                                    echo round($file->size/1024, 2) . ' KB';
                                } else {
                                    echo round($file->size/(1024*1024), 2) . ' MB';
                                }
                                @endphp
                                </p>
                            </a>
                            <form action="{{url(App::getLocale() . "/downloadFile/" . $file->id)}}">
                                <button type="submit" class="border-2 p-1 bg-green-300">{{__("files.directDownloadButtonText")}}</button>
                            </form>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
</x-app-layout>
