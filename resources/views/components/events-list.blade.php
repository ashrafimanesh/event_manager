<div class="container">
    <table class="table table-bordered yajra-datatable" id="{{$tableId}}">
        <thead>
        <tr>
            <th>{{trans('global.No')}}</th>
            <th>{{trans('global.Name')}}</th>
            <th>{{trans('global.Type')}}</th>
            <th>{{trans('global.Status')}}</th>
            <th>{{trans('global.PaymentType')}}</th>
            <th>{{trans('global.Action')}}</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
@push('adminlte_js')
    <script type="text/javascript">
        var eventsDatatable;
        function reloadEventsDatatable(){
            eventsDatatable.ajax.reload();
        }

        function startEvent(el){
            let btn = $(el);
            let submitText = btn.html();
            btn.attr('disabled','disabled');
            btn.html(btn.data('on-submit-text'));
            let postData = {
                event_id: btn.data('id'),
                _token: '{{csrf_token()}}'
            };
            $.post( '{{route('event.start')}}', postData, function(data) {
                if(data.status){
                    {!! $afterStartSuccessAction ?? '' !!}
                }
                else{
                    alert(data.message)
                }
            })
                .done(function(){

                })
                .fail(function(){
                    alert('{{trans('global.ErrorAction')}}');
                })
                .always(function(){
                    btn.removeAttr('disabled');
                    btn.html(submitText);
                });
        }

        function stopEvent(el){
            let btn = $(el);
            let submitText = btn.html();
            btn.attr('disabled','disabled');
            btn.html(btn.data('on-submit-text'));
            let postData = {
                event_id: btn.data('id'),
                _token: '{{csrf_token()}}'
            };
            $.post( '{{route('event.stop')}}', postData, function(data) {
                if(data.status){
                    {!! $afterStopSuccessAction ?? '' !!}
                }
                else{
                    alert(data.message)
                }
            })
                .done(function(){

                })
                .fail(function(){
                    alert('{{trans('global.ErrorAction')}}');
                })
                .always(function(){
                    btn.removeAttr('disabled');
                    btn.html(submitText);
                });
        }

        $(function () {

            eventsDatatable = $('#{{$tableId}}').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('events.list') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'name', name: 'name'},
                    {data: 'type_title', name: 'type_title'},
                    {data: 'status_title', name: 'status_title'},
                    {data: 'payment_type_title', name: 'payment_type_title'},
                    {
                        data: 'action',
                        name: 'action',
                        orderable: true,
                        searchable: true
                    },
                ]
            });

        });
    </script>
@endpush
