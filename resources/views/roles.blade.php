@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">{{trans('global.OrganizationManagement')}}</h1>
@stop

@section('content')
    <a class="btn btn-primary" href="{{route('user.organs', ['userId'=>$forUserOrgan->user_id])}}">{{trans('global.Back')}}</a>
    @include('partials.add-user-role', [
        'userOrgan'=>$forUserOrgan,
         'afterSuccessAction'=>'reloadUserRolesDatatable();'
     ])
    <x-card>
        <x-slot name="title">
            {!! trans('global.RolesList', ['organ'=>$forUserOrgan->organ->name]) !!}{!! ': <b>'.$forUserOrgan->user->email.'</b>' !!}
        </x-slot>
        <x-user-roles-list limit="{{$limit ?? 15}}" tableId="user-roles-data-table" userOrganId="{{$forUserOrgan->id}}"/>

    </x-card>
@stop
