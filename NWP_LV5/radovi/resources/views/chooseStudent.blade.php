@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <a class="btn btn-info mb-1" href="{{url(App::getLocale() . "/home")}}">Go back</a>
            <div class="card">
                <div class="card-header">
                    <h2>{{ __('chooseStudent.title') }}</h2>
                </div>

                <div class="card-body">
                    {{__("chooseStudent.currentlyChosenTaskText")}}
                    @if (isset($currentTask))
                        {{$currentTask->naziv_rada}}
                    @endif
                </div>
                <div class="card-footer">
                    <div class="dropdown">
                        <button type="button" class="btn btn-primary-outline dropdown-toggle" data-toggle="dropdown">{{__("chooseStudent.buttonStudents")}}</button>
                        <div class="dropdown-menu">
                            @foreach ($eligibleStudents as $item)
                            @if ($item->chosenTask()->get()->isEmpty())
                            <form action="{{url("chooseNewStudent")}}" method="post">
                                {{ csrf_field() }}
                                <input type="hidden" name="taskId" value="{{$currentTask->id}}">
                                <button type="submit" name="studentId" value="{{$item->id}}" class="dropdown-item">{{$item->name}}</button>
                            </form>
                            @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
