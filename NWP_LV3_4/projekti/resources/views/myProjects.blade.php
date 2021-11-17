@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header">{{ __('Projects you lead') }}</div>

                <div class="card-body">
                    @if (!empty($projects))
                    @foreach ($projects->toArray() as $project)
                    <div class="card mb-2">
                        <div class="card-header">
                            <a href="{{url('viewProject/' . $project["id"])}}">
                                <h2>{{$project["naziv_projekta"]}}</h2>
                            </a>
                        </div>
                        <div class="card-body">
                            Description:
                            <p>{{$project["opis_projekta"]}}</p>
                            Estimated price:
                            <p>{{$project["cijena_projekta"]}}</p>
                            Start time:
                            <p>{{$project["datum_pocetka"]}}</p>
                            Estimated end time:
                            <p>{{$project["datum_zavrsetka"]}}</p>
                            Job details:
                            <p>{{$project["obavljeni_poslovi"]}}</p>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col">
                                    Created:
                                    <p>{{$project["created_at"]}}</p>
                                </div>
                                <div class="col">
                                    Last updated:
                                    <p>{{$project["updated_at"]}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endif
                    
                    <div>
                        <a href="{{url('newProject')}}" class="btn btn-secondary">New Project</a>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header">{{ __('Projects you joined') }}</div>

                <div class="card-body">
                    @if (!empty($projects))
                    @foreach ($memberOf->toArray() as $member)
                    <div class="card mb-2">
                        <div class="card-header">
                            <a href="{{url('viewProject/' . $member["id"])}}">
                                <h2>{{$member["naziv_projekta"]}}</h2>
                            </a>
                        </div>
                        <div class="card-body">
                            Description:
                            <p>{{$member["opis_projekta"]}}</p>
                            Estimated price:
                            <p>{{$member["cijena_projekta"]}}</p>
                            Start time:
                            <p>{{$member["datum_pocetka"]}}</p>
                            Estimated end time:
                            <p>{{$member["datum_zavrsetka"]}}</p>
                            Job details:
                            <p>{{$member["obavljeni_poslovi"]}}</p>
                        </div>
                        <div class="card-footer">
                            <p></p>
                        </div>
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-2">
            <ul>
                <a href="{{url('home')}}" class="btn btn-block btn-info">Home</a>
            </ul>
        </div>
    </div>
</div>
@endsection