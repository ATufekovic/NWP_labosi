@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-3"></div><!-- Spacer -->
        <div class="col-md-6"><!-- Main body -->
            <a href="{{url('myProjects')}}" class="btn btn-secondary mb-1">Back to my projects</a>
            <div class="card">
                <div class="card-header">{{ __('New project') }}</div>

                <div class="card-body">
                    <form action="{{url("createNewProject")}}" method="post">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="projectName">New project name:</label>
                            <input type="text" name="projectName" id="projectName" class="form-control" placeholder="New project name...">
                        </div>
                        <div class="form-group">
                            <label for="projectDesc">New project description:</label>
                            <textarea name="projectDesc" id="projectDesc" cols="30" rows="10" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="projectStartTime">New project start time:</label>
                            <input type="date" name="projectStartTime" id="projectStartTime">
                        </div>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-3"><!-- Extra functionality -->
            <div class="card">
                <div class="m-2">
                    <a href="{{url('myProjects')}}" class="btn btn-block btn-info">My projects</a>
                </div>
                <div class="m-2">
                    <a href="{{url('home')}}" class="btn btn-block btn-info">Home</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection