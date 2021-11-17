<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight bg-gray-200 rounded-md p-2">
            {{__("files.headerTitle")}}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-1">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{url(App::getLocale() . "/newFile")}}" method="POST" class="font-mono p-2 flex" enctype="multipart/form-data">
                    @csrf
                    <label for="file" class="border-2 p-1 bg-green-400" id="inputLabel" onchange="inputLabelChange()">
                        <p id="inputLabelText">{{__("files.newFileChooseText")}}</p>
                        <input type="file" name="fileToUpload" id="file" class="hidden">
                    </label>

                    <select name="encryptMethod" id="encryptMethod" class="hidden" onchange="encryptMethodChange()">
                        <option value="e" hidden selected disabled>{{__("files.newFileEncryptionText")}}</option>
                        @foreach ($ciphers as $c)
                        <option value="{{$c}}">{{$c}}</option>
                        @endforeach
                    </select>

                    <select name="visibility" id="visibility" class="hidden" onchange="visibilityChange()">
                        <option value="e" hidden selected disabled>{{__("files.newFileVisibilityText")}}</option>
                        <option value="public">{{__("files.newFileVisibilityTextPublic")}}</option>
                        <option value="private">{{__("files.newFileVisibilityTextPrivate")}}</option>
                    </select>
                    <button type="submit" id="buttonSubmit" class="border-2 p-1 hidden">{{__("files.newFileUploadText")}}</button>
                </form>
                <!-- Validation Errors -->
                <x-auth-validation-errors class="p-4" :errors="$errors" />

                <script>
                    function inputLabelChange(){
                        document.getElementById('inputLabel').classList.remove('bg-green-400');
                        document.getElementById("inputLabelText").innerHTML = "Selected: " + document.getElementById("file").files.item(0).name;

                        document.getElementById("encryptMethod").classList.remove("hidden");
                        document.getElementById("encryptMethod").classList.add('bg-green-400');

                    }
                    function encryptMethodChange(){
                        document.getElementById("encryptMethod").classList.remove('bg-green-400');

                        document.getElementById("visibility").classList.remove("hidden");
                        document.getElementById("visibility").classList.add('bg-green-400');
                    }
                    function visibilityChange(){
                        document.getElementById("visibility").classList.remove('bg-green-400');

                        document.getElementById('buttonSubmit').classList.remove('hidden');
                        document.getElementById('buttonSubmit').classList.add('bg-green-400');
                    }
                </script>

            </div>
        </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
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
                            
                            <form action="{{url(App::getLocale() . "/deleteFile")}}" method="post">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="id" value="{{$file->id}}">
                                @csrf
                                <button type="submit" class="border-2 p-1 bg-red-300">{{__("files.deleteButtonText")}}</button>
                            </form>
                            <form action="{{url(App::getLocale() . "/changeVisibility")}}" method="post">
                                <input type="hidden" name="_method" value="PUT">
                                <input type="hidden" name="id" value="{{$file->id}}">
                                @csrf
                                @if ($file->visibility === "public")
                                <button type="submit" name="wish" value="private" class="border-2 p-1 bg-yellow-300">{{__("files.setPrivateButtonText")}}</button>
                                @else
                                <button type="submit" name="wish" value="public" class="border-2 p-1 bg-gray-300">{{__("files.setPublicButtonText")}}</button>
                                @endif
                            </form>
                            <form action="{{url(App::getLocale() . "/downloadFile/" . $file->id)}}">
                                <button type="submit" class="border-2 p-1 bg-green-300">{{__("files.directDownloadButtonText")}}</button>
                            </form>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</x-app-layout>
