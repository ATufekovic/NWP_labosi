@extends('layouts.app')

@section('content')
<div class="container">
    <div class="dropdown">
        <button type="button" href="#" class="dropdown-toggle btn btn-light" data-toggle="dropdown">
            {{ Config::get('languages')[App::getLocale()] }}
        </button>
        <div class="dropdown-menu">
            @foreach (Config::get('languages') as $lang => $language)
                @if ($lang != App::getLocale())
                    <a class="dropdown-item" href="{{ url($lang . "/newWork") }}">{{$language}}</a>
                @endif
            @endforeach
            </div>
    </div>
</div>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <a class="btn btn-info mb-1" href="{{url(App::getLocale() . "\home")}}">{{__("newWork.buttonHome")}}</a>
            <div class="card">
                <div class="card-header">
                    <h2>{{ __('newWork.title') }}</h2>
                </div>

                <div class="card-body">
                    <form action="{{url("createNewWork")}}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="newWorkName">{{__("newWork.inputLabelNewWorkName")}}</label>
                            <input type="text" name="newWorkName" id="newWorkname" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="newWorkNameEnglish">{{__("newWork.inputLabelNewWorkNameEnglish")}}</label>
                            <input type="text" name="newWorkNameEnglish" id="newWorkNameEnglish" class="form-control" value="default">
                        </div>
                        <div class="form-group">
                            <label for="newWorkDescription">{{__("newWork.inputLabelNewWorkDescription")}}</label>
                            <input type="text" name="newWorkDescription" id="newWorkDescription" class="form-control">
                        </div>
                        <p>{{__("newWork.inputLabelNewWorkStudyType")}}</p>
                        <div class="container">
                            <div class="form-check-inline">
                                <label for="newWorkStudyProfessionalStudy" class="form-check-label">
                                    <input class="form-check-input" id="newWorkStudyProfessionalStudy" type="radio" name="newWorkStudyType" value="professionalStudy">{{__("newWork.inputLabelNewWorkStudyTypeProfessionalStudy")}}
                                </label>
                            </div>
                            <div class="form-check-inline">
                                <label for="newWorkStudyUndergraduate" class="form-check-label">
                                    <input class="form-check-input" id="newWorkStudyUndergraduate" type="radio" name="newWorkStudyType" value="undergraduate">{{__("newWork.inputLabelNewWorkStudyTypeUndergraduate")}}
                                </label>
                            </div>
                            <div class="form-check-inline">
                                <label for="newWorkStudyGraduate" class="form-check-label">
                                    <input class="form-check-input" id="newWorkStudyGraduate" type="radio" name="newWorkStudyType" value="graduate">{{__("newWork.inputLabelNewWorkStudyTypeGraduate")}}
                                </label>
                            </div>
                        </div>
                        <button class="btn btn-primary mt-2" type="submit">{{__("newWork.inputSubmit")}}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
