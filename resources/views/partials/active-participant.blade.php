<?php
if (!\Illuminate\Support\Facades\Gate::allows(\App\Providers\AuthServiceProvider::MANAGE_PARTICIPANTS)) return;
?>
    <x-modal-form id="active_participant_form">
        <x-slot name="title">
            {{trans('global.ActiveParticipant')}}
        </x-slot>
        <input class="form-control" name="event_participant_id" type="hidden">
        <div class="form-group">
            <label>
                {{trans('global.TurnOrder')}}
            </label>
            <input class="form-control" name="turn_order" value="1">
        </div>
        <x-slot name="saveButton">
            <button onclick="submitActiveParticipantData(this);return false" class="btn btn-primary"
                    data-on-submit-text="{{trans('global.Wait')}}">{{trans('global.SaveChanges')}}</button>
        </x-slot>
        <x-slot name="footer">
            <div id="active_participant_response" class="col-md-12" style="display: none"></div>
        </x-slot>
        <x-slot name="onShow">
            formHandler.hideResponse('#active_participant_response');
        </x-slot>


    </x-modal-form>

    @push('adminlte_js')
        <script>
            function showActiveForm(el){
                $('#active_participant_form input[name="event_participant_id"]').val($(el).data('id'));
                return true;
            }
            function submitActiveParticipantData(el) {
                let event_participant_id = $('#active_participant_form input[name="event_participant_id"]').val();
                let turn_order = $('#active_participant_form input[name="turn_order"]').val();
                let responseEl = '#active_participant_response';
                if (!event_participant_id || !turn_order) {
                    formHandler.showResponse(responseEl, '{{trans('global.InvalidInput')}}', true);
                    return false;
                }
                let btn = $(el);
                let submitText = btn.html();
                btn.attr('disabled', 'disabled');
                btn.html(btn.data('on-submit-text'));
                let postData = {
                    event_participant_id: event_participant_id,
                    turn_order: turn_order,
                    _token: '{{csrf_token()}}'
                };
                $.post('{{route('participant.active', ['eventId'=>$eventModel->id])}}', postData, function (data) {
                    formHandler.handleResponse(responseEl, data);
                    if (data.status) {
                        {!! $afterSuccessAction ?? '' !!}
                    }
                })
                    .done(function () {

                    })
                    .fail(function () {
                        formHandler.failed(responseEl);
                    })
                    .always(function () {
                        btn.removeAttr('disabled');
                        btn.html(submitText);
                    });
            }
        </script>
    @endpush
