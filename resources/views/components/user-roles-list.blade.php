<div class="container">
    <table class="table table-bordered yajra-datatable" id="{{$tableId}}">
        <thead>
        <tr>
            <th>{{trans('global.No')}}</th>
            <th>{{trans('global.RoleName')}}</th>
            <th>{{trans('global.Action')}}</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
@push('adminlte_js')
    <script type="text/javascript">
        var userRolesDatatable;
        function reloadUserRolesDatatable(){
            userRolesDatatable.ajax.reload();
        }

        $(function () {

            userRolesDatatable = $('#{{$tableId}}').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('user.roles_data', ['userOrganId'=>$userOrganId]) }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'role_name', name: 'name'},
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
