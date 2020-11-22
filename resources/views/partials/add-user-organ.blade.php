<?php
/** @var \App\User $user */
/** @var \Illuminate\Support\Collection $organs */
$organs = \App\Organ::query()->exceptUserOrgans($user->id)->get();
?>
@if($organs->count() > 0)
    <x-modal-form id="user_organ_form">
        <x-slot name="title">
            {{trans('global.AddData')}}
        </x-slot>
        <div class="form-group">
            <label>
                {{trans('global.OrganName')}}
            </label>
            <select name="organ_id" id="organ_id">
                @foreach($organs as $organ)
                    <option value="{{$organ->id}}">{{$organ->name}}</option>
                @endforeach
            </select>
        </div>
        <x-slot name="saveButton">
            <button onclick="submitUserOrganData(this);return false" class="btn btn-primary"  data-on-submit-text="{{trans('global.Wait')}}">{{trans('global.SaveChanges')}}</button>
        </x-slot>
        <x-slot name="footer">
            <div id="add_user_organ_response" class="col-md-12" style="display: none"></div>
        </x-slot>
        <x-slot name="onShow">
            formHandler.hideResponse('#add_user_organ_response');
        </x-slot>


    </x-modal-form>

    <button class="btn btn-primary" data-toggle="modal" data-target="#user_organ_form">
        {{trans('global.AddOrgan')}}
    </button>
@push('adminlte_js')
    <script>
        function submitUserOrganData(el){
            let organ_id = $("#organ_id").val();
            let responseEl = '#add_user_organ_response';
            if(!organ_id){
                formHandler.showResponse(responseEl,'{{trans('global.InvalidInput')}}', true);
                return false;
            }
            let btn = $(el);
            let submitText = btn.html();
            btn.attr('disabled','disabled');
            btn.html(btn.data('on-submit-text'));
            $.post( '{{route('user.add_organ', ['userId'=>$user->id])}}', {organ_id: organ_id, _token: '{{csrf_token()}}'}, function(data) {
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

