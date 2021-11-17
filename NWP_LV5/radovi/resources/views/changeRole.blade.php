@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2>{{ __('changeRole.title') }}</h2>
                </div>

                <div class="card-body">
                    @foreach (App\Models\User::all() as $user)
                    <div class="card my-1">
                        <div class="card-header">
                            <p>{{$user->name}}</p>
                        </div>
                        <div class="card-body">
                            <form action="{{url("/changeRoleOfUser")}}" method="post">
                                @csrf
                                <input type="hidden" name="userId" value="{{$user->id}}">
                                <div class="dropdown">
                                    <button type="button" class="btn btn-dark dropdown-toggle" data-toggle="dropdown">
                                        @if ($user->role_id == 1)
                                        {{__("changeRole.admin")}}
                                        @elseif ($user->role_id == 2)
                                        {{__("changeRole.professor")}}
                                        @elseif ($user->role_id == 3)
                                        {{__("changeRole.student")}}
                                        @endif
                                    </button>
                                    <div class="dropdown-menu">
                                        <button type="submit" href="#" name="role" value="admin" class="dropdown-item">{{__("changeRole.admin")}}</button>
                                        <button type="submit" href="#" name="role" value="professor" class="dropdown-item">{{__("changeRole.professor")}}</button>
                                        <button type="submit" href="#" name="role" value="student" class="dropdown-item">{{__("changeRole.student")}}</button>
                                    </div>
                                </div>
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
