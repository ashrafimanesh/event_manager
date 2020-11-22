    <x-modal-form id="organ_form">
        <x-slot name="title">
            {{trans('global.AddData')}}
        </x-slot>
        <div class="form-group">
            <label>
                {{trans('global.OrganName')}}
            </label>
            <input name="organ_name" id="organ_name">
        </div>
        <x-slot name="saveButton">
            <button onclick="submitOrganData(this);return false" class="btn btn-primary"  data-on-submit-text="{{trans('global.Wait')}}">{{trans('global.SaveChanges')}}</button>
        </x-slot>
        <x-slot name="footer">
            <div id="add_organ_response" class="col-md-12" style="display: none"></div>
        </x-slot>
        <x-slot name="onShow">
            formHandler.hideResponse('#add_organ_response');
        </x-slot>


    </x-modal-form>

    <button class="btn btn-primary" data-toggle="modal" data-target="#organ_form">
        {{trans('global.AddOrgan')}}
    </button>
    @push('adminlte_js')
        <script>
            function submitOrganData(el){
                let organ_name = $("#organ_name").val();
                let responseEl = '#add_organ_response';
                if(!organ_name){
                    formHandler.showResponse(responseEl,'{{trans('global.InvalidInput')}}', true);
                    return false;
                }
                let btn = $(el);
                let submitText = btn.html();
                btn.attr('disabled','disabled');
                btn.html(btn.data('on-submit-text'));
                $.post( '{{route('organs.store')}}', {name: organ_name, _token: '{{csrf_token()}}'}, function(data) {
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
