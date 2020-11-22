    <x-modal-form id="user_form">
        <x-slot name="title">
            {{trans('global.AddData')}}
        </x-slot>
        <div class="form-group">
            <label>
                {{trans('global.Name')}}
            </label>
            <input name="user_name" id="user_name">
        </div>
        <div class="form-group">
            <label>
                {{trans('global.Email')}}
            </label>
            <input name="user_email" type="email" id="user_email">
        </div>
        <x-slot name="saveButton">
            <button onclick="submitUserData(this);return false" class="btn btn-primary"  data-on-submit-text="{{trans('global.Wait')}}">{{trans('global.SaveChanges')}}</button>
        </x-slot>
        <x-slot name="footer">
            <div id="add_user_response" class="col-md-12" style="display: none"></div>
        </x-slot>
        <x-slot name="onShow">
            formHandler.hideResponse('#add_user_response');
            $("#user_name").val('');
            $("#user_email").val('');
        </x-slot>


    </x-modal-form>

    <button class="btn btn-primary" data-toggle="modal" data-target="#user_form">
        {{trans('global.AddUser')}}
    </button>
    @push('adminlte_js')
        <script>
            function validateEmail(mail)
            {
                if (/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(mail))
                {
                    return true;
                }
                return false;
            }

            function submitUserData(el){
                let name = $("#user_name").val();
                let email = $("#user_email").val();
                let responseEl = '#add_user_response';
                if(!name || !email){
                    formHandler.showResponse(responseEl,'{{trans('global.InvalidInput')}}', true);
                    return false;
                }
                if(!validateEmail(email)){
                    formHandler.showResponse(responseEl, '{{trans('global.InvalidEmail')}}', true);
                    return false;
                }
                let btn = $(el);
                let submitText = btn.html();
                btn.attr('disabled','disabled');
                btn.html(btn.data('on-submit-text'));
                $.post( '{{route('users.store')}}', {name: name, email: email, _token: '{{csrf_token()}}'}, function(data) {
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
