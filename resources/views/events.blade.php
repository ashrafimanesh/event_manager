@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">{{trans('global.EventsManagement')}}</h1>
@stop

@section('content')
        @include('partials.add-event',[
            'forOrgan'=>$forOrgan ?? null,
            'afterSuccessAction'=>'reloadEventsDatatable();',
            ])
    <x-card>
        <x-slot name="title">
            {{trans('global.EventsList')}}{!! !empty($forOrgan) ? ': <b>'.$forOrgan->name.'</b>' : '' !!}
        </x-slot>
        <x-events-list limit="{{$limit ?? 15}}" tableId="events-data-table" afterStartSuccessAction="reloadEventsDatatable();" afterStopSuccessAction="reloadEventsDatatable();"/>

    </x-card>
@stop
