@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">{{trans('global.Profile')}}</h1>
@stop

@section('content')
    <x-card>
        <x-slot name="title">
            {{trans('global.Profile')}}
        </x-slot>
        <div class="form-group">
            <label>{{trans('global.Name')}}:</label>
            {{$user->name}}
        </div>
        <div class="form-group">
            <label>{{trans('global.Email')}}:</label>
            {{$user->email}}
        </div>
        <div class="form-group">
            <label>{{trans('global.Organization')}}:</label>
            @if($user->organs_count>1)
                <form action="{{url('change_organ')}}" method="post">
                <select class="form-control" name="user_organ_id">
                    @foreach($user->getOrgans() as $userOrgan)
                        @if($userOrgan->id == $user->getCurrentUserOrganId())
                            <option value="{{$userOrgan->id}}" selected="selected">{{$userOrgan->organ->name}}</option>
                        @else
                            <option value="{{$userOrgan->id}}">{{$userOrgan->organ->name}}</option>
                        @endif
                    @endforeach
                </select>
                    {{csrf_field()}}
                    <input type="submit" class="btn btn-primary" value="{{trans('global.ChangeOrgan')}}"/>
                </form>
            @else
                {!! $user->organs_name !!}
            @endif
        </div>
        <div class="form-group">
            <label>{{trans('global.Roles')}}:</label>
            {{$user->roles_name}}
        </div>
        <div class="col-md-12">
            <form action="{{route('logout')}}" method="post">
                {{csrf_field()}}
                <input type="submit" class="btn btn-primary" value="{{trans('global.Logout')}}"/>
            </form>
        </div>
    </x-card>
@stop
