@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2>{{ __('home.dashboard') }}</h2>
                    @if (auth()->user()->role_id == 1)
                    <p>Rank: {{__("app.admin")}}</p>
                    @elseif (auth()->user()->role_id == 2)
                    <p>Rank: {{__("app.professor")}}</p>
                    @else
                    <p>Rank: {{__("app.student")}}</p>
                    @endif
                </div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    @if (auth()->user()->role_id == "1")
                    <a class="btn btn-dark" href="{{url(App::getLocale() . "/changeRole")}}">{{__("home.buttonAdminChangeRole")}}</a>

                    @elseif (auth()->user()->role_id == "2")
                    <a class="btn btn-primary" href="{{ url(App::getLocale() . "/newWork") }}">{{__("home.buttonProfAddWork")}}</a>
                    @foreach (auth()->user()->tasks()->get() as $item)
                    <div class="card my-1">
                        <div class="card-header">
                            <h2>{{$item->naziv_rada}}</h2>
                            <p>{{$item->naziv_rada_engleski}}</p>
                        </div>
                        <div class="card-body">
                            {{__("home.workDescription")}}
                            <p>{{$item->zadatak_rada}}</p>
                            {{__("home.workStudyType")}}
                            <p>
                            @if ($item->tip_studija == "stručni")
                            {{__("newWork.inputLabelNewWorkStudyTypeProfessionalStudy")}}
                            @elseif ($item->tip_studija == "preddiplomski")
                            {{__("newWork.inputLabelNewWorkStudyTypeUndergraduate")}}
                            @elseif ($item->tip_studija == "diplomski")
                            {{__("newWork.inputLabelNewWorkStudyTypeGraduate")}}
                            @endif
                            </p>
                            {{__("home.workCreatedAt")}}
                            <p>{{$item->created_at}}</p>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col">
                                    {{__("home.professorCurrentlyChosenStudent")}}
                                    <p>
                                        @if ($item->chosenStudent()->get()->isNotEmpty())
                                        {{$item->chosenStudent()->get()[0]->name}}
                                        @else
                                         - 
                                        @endif
                                    </p>
                                </div>
                                <div class="col">
                                    <a class="btn btn-primary" href="{{url(App::getLocale() . "/chooseStudent/" . $item->id)}}">{{__("home.professorButtonChooseStudent")}}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    @elseif (auth()->user()->role_id == "3")
                    @if (auth()->user()->chosenTask()->get()->isEmpty())
                    @php
                    $studentTasks = auth()->user()->appliedTo()->get();
                    $orderedTasks = [1,2,3,4,5];
                    for ($i=0; $i < 5; $i++) { 
                        foreach ($studentTasks as $task) {
                            if($orderedTasks[$i] == $task->pivot->order){
                                $orderedTasks[$i] = $task;
                            }
                        }
                    }
                    @endphp
                    <div class="container">
                        <h2>{{__("home.studentTasksTitle")}}</h2>
                        <p>{{__("home.studentTasksSubtitle")}}</p>

                        <table class="table-bordered table-hover table">
                            <thead>
                                <tr>
                                    <th>{{__("home.studentTasksTableTaskHeader")}}</th>
                                    <th class="align-middle text-center"></th>
                                </tr>
                            </thead>
                            <tbody>
                            @for ($i = 0; $i < 5; $i++)
                                <tr>
                                @if(is_object($orderedTasks[$i]))
                                    <td>{{$orderedTasks[$i]->naziv_rada}}</td>
                                    <td class="align-middle text-center"><a class="btn btn-info" href="{{url(App::getLocale() . "/chooseTask/" . ($i+1))}}">{{__("home.studentTasksButtonChooseTask")}}</a></td>
                                @else
                                    <td> - </td>
                                    <td class="align-middle text-center"><a class="btn btn-info" href="{{url(App::getLocale() . "/chooseTask/" . ($i+1))}}">{{__("home.studentTasksButtonChooseTask")}}</a></td>
                                @endisset
                                </tr>
                            @endfor
                            </tbody>
                        </table>
                    </div>
                    @else
                    {{__("home.studentChosenTask")}}
                    @php
                    $item = auth()->user()->chosenTask()->get()[0];
                    @endphp
                    <div class="card my-1">
                        <div class="card-header">
                            <h2>{{$item->naziv_rada}}</h2>
                            <p>{{$item->naziv_rada_engleski}}</p>
                        </div>
                        <div class="card-body">
                            {{__("home.workDescription")}}
                            <p>{{$item->zadatak_rada}}</p>
                            {{__("home.workStudyType")}}
                            <p>
                            @if ($item->tip_studija == "stručni")
                            {{__("newWork.inputLabelNewWorkStudyTypeProfessionalStudy")}}
                            @elseif ($item->tip_studija == "preddiplomski")
                            {{__("newWork.inputLabelNewWorkStudyTypeUndergraduate")}}
                            @elseif ($item->tip_studija == "diplomski")
                            {{__("newWork.inputLabelNewWorkStudyTypeGraduate")}}
                            @endif
                            </p>
                            {{__("home.workCreatedAt")}}
                            <p>{{$item->created_at}}</p>
                        </div>
                    </div>
                    @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
