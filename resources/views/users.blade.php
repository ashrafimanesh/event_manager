@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">{{trans('global.UsersManagement')}}</h1>
@stop

@section('content')
    @include('partials.add-user', ['afterSuccessAction'=>'reloadUsersDatatable();'])
    <x-card>
        <x-slot name="title">
            {{trans('global.UsersList')}}
        </x-slot>
        <x-users-list limit="{{$limit ?? 15}}" tableId="users-data-table"/>
    </x-card>
@stop
