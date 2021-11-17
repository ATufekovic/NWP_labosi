@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <a class="btn btn-info mb-1" href="{{url(App::getLocale() . "/home")}}">Go back</a>
            <div class="card">
                <div class="card-header">
                    <h2>{{ __('chooseTask.title') }}</h2>
                </div>

                <div class="card-body">
                    {{__("chooseTask.currentlyChosenTaskText")}}
                    <p>
                    @if (isset($currentlyChosenTask))
                    {{$currentlyChosenTask->naziv_rada}}
                    @else
                     - 
                    @endif
                    </p>
                </div>
                <div class="card-footer">
                    @foreach (\App\Models\Task::all() as $item)
                    <div class="card mt-1">
                        <div class="card-header">
                            <p>{{$item->naziv_rada}}</p>
                            <p>{{$item->naziv_rada_engleski}}</p>
                        </div>
                        <div class="card-body">
                            <p>{{$item->zadatak_rada}}</p>
                            <p>
                            @if ($item->tip_studija == "stručni")
                            {{__("newWork.inputLabelNewWorkStudyTypeProfessionalStudy")}}
                            @elseif ($item->tip_studija == "preddiplomski")
                            {{__("newWork.inputLabelNewWorkStudyTypeUndergraduate")}}
                            @elseif ($item->tip_studija == "diplomski")
                            {{__("newWork.inputLabelNewWorkStudyTypeGraduate")}}
                            @endif
                            </p>
                        </div>
                        <div class="card-footer">
                            <form action="{{url("/chooseTargetedTask")}}" method="post">
                                @csrf
                                <input type="hidden" name="taskId" value="{{$item->id}}">
                                <input type="hidden" name="order" value="{{request()->segment(count(request()->segments()))}}">
                                <button type="submit" class="btn btn-danger">{{__("chooseTask.buttonChooseNewTask")}}</button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
