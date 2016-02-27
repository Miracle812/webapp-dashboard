@section('title')
    Cleaning schedules tasks list
    @parent
@endsection
@section('content')
    <div id="page_content">
        <div id="page_content_inner">
            <h2 class="heading_b uk-margin-bottom">Cleaning schedules</h2>
            <div class="md-card">
                <div class="md-card-toolbar">
                    <h3 class="md-card-toolbar-heading-text large">
                        Tasks list
                    </h3>
                </div>
                <div class="md-card-content">
                    <table class="uk-table uk-table-nowrap uk-table-valign-middle" id="dataTable" date-filter="true"  data-source="{{URL::to("/new-cleaning-schedule/tasks-list-datatable")}}">
                        <thead>
                        <tr>
                            <th></th>
                            <th>Task {{Lang::get('common/general.name')}}</th>
                            <th>{{Lang::get('common/general.staff')}}</th>
                            <th>{{Lang::get('common/general.form_name')}}</th>
                            <th>{{Lang::get('common/general.start')}}</th>
                            <th>{{Lang::get('common/general.repeat_to')}}</th>
                            <th class="text-center">{{Lang::get('common/general.tasks')}}</th>
                            <th class="text-center">{{Lang::get('common/general.edit')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript" src="{{ asset('newassets/packages/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('newassets/js/custom/datatables_uikit.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            var $dataTable = $('#dataTable');
            if($dataTable.length) {
                $dataTable.DataTable({
                    "ajax": $dataTable.data('source'),
                    "columnDefs": [{
                        "targets": [ 0 ],
                        "visible": false,
                        "searchable": false
                    }, {
                        orderable: false, targets:[4,5,6]
                    }]
                })
            }
        });
    </script>
@endsection