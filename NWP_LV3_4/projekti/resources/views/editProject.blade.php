@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <a href="{{url("viewProject/" . $project->id)}}" class="btn btn-primary mb-1">Go back</a>
            @php
            echo '<form action="';
            if($isLeader){
                echo url("saveChanges");
            } else {
                echo url("saveDetails");
            }
            echo '" method="post">';
            @endphp
                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{$project->id}}">
                @if ($isLeader)
                <div class="card mb-1">
                    <div class="card-header">
                        <h2>{{$project->naziv_projekta}}</h2>
                        <div class="form-group">
                            <label for="newProjectName">New project name:</label>
                            <input class="form-control" type="text" name="newProjectName" id="newProjectName" value="{{$project->naziv_projekta}}">
                        </div>
                    </div>
                    <div class="card-body">
                        Description:
                        <p>{{$project->opis_projekta}}</p>
                        <div class="form-group">
                            <label for="newProjectDesc">New project description:</label>
                            <input class="form-control" type="text" name="newProjectDesc" id="newProjectDesc" value="{{$project->opis_projekta}}">
                        </div>
                        Estimated price:
                        <p>{{$project->cijena_projekta}}</p>
                        <div class="form-group">
                            <label for="newProjectPrice">New project price:</label>
                            <input class="form-control" type="text" name="newProjectPrice" id="newProjectPrice" value="{{$project->cijena_projekta}}">
                        </div>
                        Start time:
                        <p>{{$project->datum_pocetka}}</p>
                        <div class="form-group">
                            <label for="newProjectStartTime">New project start time:</label>
                            <input class="form-control" type="date" name="newProjectStartTime" id="newProjectStartTime" value="{{date("Y-m-d", strtotime($project->datum_pocetka))}}">
                        </div>
                        Estimated end time:
                        <p>{{$project->datum_zavrsetka}}</p>
                        <div class="form-group">
                            <label for="newProjectEndTime">New project end time:</label>
                            <input class="form-control" type="date" name="newProjectEndTime" id="newProjectEndTime" value="{{date("Y-m-d", strtotime($project->datum_zavrsetka))}}">
                        </div>
                        Job details:
                        <p>{{$project->obavljeni_poslovi}}</p>
                        <div class="form-group">
                            <label for="newProjectJobDetails">New project job details:</label>
                            <textarea class="form-control" type="text" name="newProjectJobDetails" id="newProjectJobDetails" value="{{$project->obavljeni_poslovi}}">{{$project->obavljeni_poslovi}}</textarea>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col">
                                Created:
                                <p>{{$project->created_at}}</p>
                            </div>
                            <div class="col">
                                Last updated:
                                <p>{{$project->updated_at}}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="card mb-1">
                    <div class="card-header">
                        <h2>{{$project->naziv_projekta}}</h2>
                    </div>
                    <div class="card-body">
                        Description:
                        <p>{{$project->opis_projekta}}</p>
                        Estimated price:
                        <p>{{$project->cijena_projekta}}</p>
                        Start time:
                        <p>{{$project->datum_pocetka}}</p>
                        Estimated end time:
                        <p>{{$project->datum_zavrsetka}}</p>
                        Job details:
                        <p>{{$project->obavljeni_poslovi}}</p>
                        <div class="form-group">
                            <label for="newProjectDetails">New project description:</label>
                            <textarea class="form-control" type="text" name="newProjectJobDetails" id="newProjectJobDetails" value="{{$project->obavljeni_poslovi}}">{{$project->obavljeni_poslovi}}</textarea>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col">
                                Created:
                                <p>{{$project->created_at}}</p>
                            </div>
                            <div class="col">
                                Last updated:
                                <p>{{$project->updated_at}}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                
                <button type="submit" class="btn btn-primary mb-1">Save changes</button>
            </form>
            <div class="card">
                <div class="card-header">
                    <p>Users</p>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush mb-1">
                        <li class="list-group-item">{{$leader->name}}</li>
                        @foreach ($members as $member)
                        <li class="list-group-item">{{$member->name}}</li>
                        @endforeach
                    </ul>
                    @if ($isLeader)
                    <div>
                        <form action="{{url("addMember/" . $project->id)}}" method="post">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="selectNotMemberId">Select new user:</label>
                                <select class="form-control" name="selectNotMemberId" id="selectNotMemberId">
                                    <option value="default">- -</option>
                                    @foreach ($notMembers as $notMember)
                                    <option value="{{$notMember->id}}">{{$notMember->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Add new member</button>
                        </form>
                    </div>
                    <div>
                        <form action="{{url("removeMember/" . $project->id)}}" method="post">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="selectMemberId">Select existing member:</label>
                                <select class="form-control" name="selectMemberId" id="selectMemberId">
                                    <option value="default">- -</option>
                                    @foreach ($members as $member)
                                    <option value="{{$member->id}}">{{$member->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Remove member</button>
                        </form>
                    </div>
                    @else
                    <div><p>Only the leader can edit memberships</p></div>
                    @endif
                    

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