<?php
/** @var \App\OrganUser $userOrgan */
/** @var \Illuminate\Support\Collection $roles */
$roles = \App\Role::query()->exceptUserRoles($userOrgan->id)->get();
?>
@if($roles->count() > 0)
    <x-modal-form id="user_role_form">
        <x-slot name="title">
            {{trans('global.AddData')}}
        </x-slot>
        <div class="form-group">
            <label>
                {{trans('global.RoleName')}}
            </label>
            <select name="role_id" id="role_id">
                @foreach($roles as $role)
                    <option value="{{$role->id}}">{{$role->name}}</option>
                @endforeach
            </select>
        </div>
        <x-slot name="saveButton">
            <button onclick="submitUserRoleData(this);return false" class="btn btn-primary"  data-on-submit-text="{{trans('global.Wait')}}">{{trans('global.SaveChanges')}}</button>
        </x-slot>
        <x-slot name="footer">
            <div id="add_user_role_response" class="col-md-12" style="display: none"></div>
        </x-slot>
        <x-slot name="onShow">
            formHandler.hideResponse('#add_user_role_response');
        </x-slot>


    </x-modal-form>

    <button class="btn btn-primary" data-toggle="modal" data-target="#user_role_form">
        {{trans('global.AddRole')}}
    </button>
@push('adminlte_js')
    <script>
        function submitUserRoleData(el){
            let role_id = $("#role_id").val();
            let responseEl = '#add_user_role_response';
            if(!role_id){
                formHandler.showResponse(responseEl,'{{trans('global.InvalidInput')}}', true);
                return false;
            }
            let btn = $(el);
            let submitText = btn.html();
            btn.attr('disabled','disabled');
            btn.html(btn.data('on-submit-text'));
            $.post( '{{route('user.add_role', ['userOrganId'=>$userOrgan->id])}}', {role_id: role_id, _token: '{{csrf_token()}}'}, function(data) {
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

