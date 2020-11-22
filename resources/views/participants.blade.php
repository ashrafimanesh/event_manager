@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">{{trans('global.ParticipantsManagement')}}</h1>
@stop

@section('content')
    <a class="btn btn-primary" href="{{route('events.index')}}">{{trans('global.Back')}}</a>
    @include('partials.active-participant', [
        'eventModel'=>$eventModel,
         'afterSuccessAction'=>'reloadParticipantsDatatable()'
    ])
    <x-card>
        <x-slot name="title">
            {{trans('global.ParticipantList')}}{!! ': <b>'.$eventModel->name.'</b>' !!}
        </x-slot>
        <x-participants-list limit="{{$limit ?? 15}}" eventId="{{$eventModel->id}}" tableId="participants-data-table"/>

    </x-card>
@stop
@push('adminlte_js')
    <script>

    </script>
@endpush
