<div class="container">
    <table class="table table-bordered yajra-datatable" id="{{$tableId}}">
        <thead>
        <tr>
            <th>{{trans('global.No')}}</th>
            <th>{{trans('global.Name')}}</th>
            <th>{{trans('global.Status')}}</th>
            <th>{{trans('global.TurnOrder')}}</th>
            <th>{{trans('global.Action')}}</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
@push('adminlte_js')
    <script type="text/javascript">
        var participantsDatatable;
        function reloadParticipantsDatatable(){
            participantsDatatable.ajax.reload();
        }

        $(function () {

            participantsDatatable = $('#{{$tableId}}').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('event.participants_data', ['eventId'=>$eventId]) }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'participant_name', name: 'participant_name'},
                    {data: 'status_title', name: 'status_title'},
                    {data: 'turn_order', name: 'turn_order'},
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
