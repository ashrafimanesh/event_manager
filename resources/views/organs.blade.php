@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">{{trans('global.OrganizationManagement')}}</h1>
@stop

@section('content')
    <a class="btn btn-primary" href="{{route('users.index')}}">{{trans('global.Back')}}</a>
    @if(\Illuminate\Support\Facades\Gate::allows(\App\Providers\AuthServiceProvider::MANAGE_ORGANIZATION))
        @if(empty($forUser))
            @include('partials.add-organ',['afterSuccessAction'=>'reloadOrgansDatatable();'])
        @else
            @include('partials.add-user-organ', [
                'user'=>$forUser,
                 'afterSuccessAction'=>'reloadUserOrgansDatatable();'
             ])
        @endif
    @endif
    <x-card>
        <x-slot name="title">
            {{trans('global.OrganizationList')}}{!! !empty($forUser) ? ': <b>'.$forUser->email.'</b>' : '' !!}
        </x-slot>
        @if(!empty($forUser))
            <x-user-organs-list limit="{{$limit ?? 15}}" tableId="user-organs-data-table" userId="{{$forUser->id}}"/>
        @else
            <x-organs-list limit="{{$limit ?? 15}}" tableId="organs-data-table"/>
        @endif

    </x-card>
@stop
