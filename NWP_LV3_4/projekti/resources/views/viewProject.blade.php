@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <a href="{{url("editProject/" . $project["project"]->id)}}" class="btn btn-primary mb-1">Edit project</a>
            <div class="card mb-2">
                <div class="card-header">
                    <h2>{{$project["project"]->naziv_projekta}}</h2>
                </div>
                <div class="card-body">
                    Description:
                    <p>{{$project["project"]->opis_projekta}}</p>
                    Estimated price:
                    <p>{{$project["project"]->cijena_projekta}}</p>
                    Start time:
                    <p>{{$project["project"]->datum_pocetka}}</p>
                    Estimated end time:
                    <p>{{$project["project"]->datum_zavrsetka}}</p>
                    Job details:
                    <p>{{$project["project"]->obavljeni_poslovi}}</p>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col">
                            Created:
                            <p>{{$project["project"]->created_at}}</p>
                        </div>
                        <div class="col">
                            Last updated:
                            <p>{{$project["project"]->updated_at}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <p>Users</p>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush mb-1">
                        <li class="list-group-item">{{$members["users"][0][0]["name"]}}</li>
                        @foreach ($members["users"][1] as $member)
                        <li class="list-group-item">{{$member["name"]}}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="m-2">
                <a href="{{url('myProjects')}}" class="btn btn-block btn-info">My projects</a>
            </div>
        </div>
    </div>
</div>
@endsection