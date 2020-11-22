<?php
if(!\Illuminate\Support\Facades\Gate::allows(\App\Providers\AuthServiceProvider::MANAGE_EVENTS)) return;

/** @var \Illuminate\Support\Collection $organs */
$organs = \App\Organ::query()->get();
$types = \App\EventModel::allTypes();
?>
@if($organs->count()>0)
    <x-modal-form id="event_form">
        <x-slot name="title">
            {{trans('global.AddData')}}
        </x-slot>
        <div class="form-group">
            <label>
                {{trans('global.EventName')}}
            </label>
            <input class="form-control" name="event_name" id="event_name">
        </div>
        <div class="form-group">
            <label>
                {{trans('global.EventType')}}
            </label>
            <select class="form-control" name="type" id="type">
                @foreach($types as $id=>$type)
                    <option value="{{$id}}">{{$type['title']}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>
                {{trans('global.RecurringIntervals')}}
            </label>
            <input class="form-control" name="recurring_intervals" id="recurring_intervals" value="0">
        </div>
        <div class="form-group">
            <label>
                {{trans('global.OrganName')}}
            </label>
            <select class="form-control" name="organ_id" id="organ_id">
                @foreach($organs as $organ)
                    @if((!empty($forOgan) && $organ->id == $forOgan->id))
                        <option value="{{$organ->id}}" selected="selected">{{$organ->name}}</option>
                    @else
                        <option value="{{$organ->id}}">{{$organ->name}}</option>
                    @endif
                @endforeach
            </select>
        </div>
        <x-slot name="saveButton">
            <button onclick="submitEventData(this);return false" class="btn btn-primary"  data-on-submit-text="{{trans('global.Wait')}}">{{trans('global.SaveChanges')}}</button>
        </x-slot>
        <x-slot name="footer">
            <div id="add_event_response" class="col-md-12" style="display: none"></div>
        </x-slot>
        <x-slot name="onShow">
            formHandler.hideResponse('#add_event_response');
        </x-slot>


    </x-modal-form>

    <button class="btn btn-primary" data-toggle="modal" data-target="#event_form">
        {{trans('global.AddEvent')}}
    </button>
    @push('adminlte_js')
        <script>
            function submitEventData(el){
                let event_name = $("#event_name").val();
                let type = $("#type").val();
                let organ_id = $("#organ_id").val();
                let recurring_intervals = $("#recurring_intervals").val();
                let responseEl = '#add_event_response';
                if(!event_name || !organ_id || !type){
                    formHandler.showResponse(responseEl,'{{trans('global.InvalidInput')}}', true);
                    return false;
                }
                let btn = $(el);
                let submitText = btn.html();
                btn.attr('disabled','disabled');
                btn.html(btn.data('on-submit-text'));
                let postData = {
                    name: event_name,
                    type: type,
                    organ_id: organ_id,
                    recurring_intervals: recurring_intervals,
                    _token: '{{csrf_token()}}'
                };
                $.post( '{{route('events.store')}}', postData, function(data) {
                    formHandler.handleResponse(responseEl, data);
                    if(data.status){
                        {!! $afterSuccessAction ?? '' !!}
                    }
                })
                    .done(function(){

                    })
                    .fail(function(){
                        formHandler.failed(responseEl);
                    })
                    .always(function(){
                        btn.removeAttr('disabled');
                        btn.html(submitText);
                    });
            }
        </script>
    @endpush
@endif
