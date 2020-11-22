<div class="modal fade" id="{{$id}}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{$title}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {!! $slot !!}
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{trans('global.Close')}}</button>
                {!! $saveButton ?? '' !!}
                {!! $footer ?? '' !!}
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<script>
    let formHandler = {
        showResponse: function(el, html, error){
            if(error){
                html = '<div class="callout callout-danger bg-red">'+html+'</div>';
            }
            else{
                html = '<div class="callout callout-success bg-green">'+html+'</div>';
            }
            $(el).html(html);
            $(el).show();
        },
        hideResponse : function (el){
            $(el).html('');
            $(el).hide()
        },
        handleResponse: function(el, data){
            try {
                if(data.status != undefined){
                    formHandler.showResponse(el, data.message, !data.status);
                }
            }catch (e) {
                console.log(e);
            }
        },
        failed: function(el){
            formHandler.showResponse(el, '{{trans('global.ErrorAction')}}', true);
        }
    }
</script>
@push('adminlte_js')
    <script>
        $(function(){
            $("#{{$id}}").on('show.bs.modal', function(){
                let _this = this;
                {!! $onShow ?? '' !!}
            });
        })
    </script>
@endpush
