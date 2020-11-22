<div class="container">
    <table class="table table-bordered yajra-datatable" id="{{$tableId}}">
        <thead>
        <tr>
            <th>{{trans('global.No')}}</th>
            <th>{{trans('global.OrganName')}}</th>
            <th>{{trans('global.Action')}}</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
@push('adminlte_js')
    <script type="text/javascript">
        var userOrgansDatatable;
        function reloadUserOrgansDatatable(){
            userOrgansDatatable.ajax.reload();
        }

        $(function () {

            userOrgansDatatable = $('#{{$tableId}}').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('user.organs_data', ['userId'=>$userId]) }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'organ_name', name: 'name'},
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
