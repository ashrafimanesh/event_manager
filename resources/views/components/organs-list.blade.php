<div class="container">
    <table class="table table-bordered yajra-datatable" id="{{$tableId}}">
        <thead>
        <tr>
            <th>{{trans('global.No')}}</th>
            <th>{{trans('global.OrganName')}}</th>
            <th>{{trans('global.UsersCount')}}</th>
            <th>{{trans('global.Action')}}</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
@push('adminlte_js')
    <script type="text/javascript">
        var organsDatatable;
        function reloadOrgansDatatable(){
            organsDatatable.ajax.reload();
        }
        $(function () {

            organsDatatable = $('#{{$tableId}}').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('organs.list') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'name', name: 'name'},
                    {data: 'users_count', name: 'users_count'},
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
