@extends('newlayout.base')
@section('title')
    @parent :: {{$sectionName}}
@endsection
@section('content')

    <div id="page_content">
        <div id="page_content_inner">

            <h2 class="heading_b uk-margin-bottom">{{$sectionName}}
                <span class="panel-action">
                    <a class="md-btn md-btn-primary md-btn-wave-light waves-effect waves-button waves-light" href="{{URL::to('/health-questionnaires/forms-list')}}"><i class="material-icons">add</i> {{Lang::get('common/button.create')}} </a>
                    <a class="md-btn md-btn-primary md-btn-wave-light waves-effect waves-button waves-light" href="{{URL::to('/health-questionnaires/submitted')}}"><i class="material-icons">search</i> {{Lang::get('common/general.datatable')}} </a>
                </span>
            </h2>

            <?php /*@include('breadcrumbs',['data' => $breadcrumbs]) */?>

            <div class="md-card">
                <div class="md-card-content">

                    <table class="uk-table uk-table-striped dataTable" id="dataTable" date-filter="true"  data-source="{{URL::to("/health-questionnaires/staff/$staff->id/datatable")}}">
                        <thead>
                            <tr>
                                <th></th>
                                <th>{{Lang::get('common/general.created')}}</th>
                                <th>{{Lang::get('common/sections.staff.title')}}</th>
                                <th>{{Lang::get('common/general.form_name')}}</th>
                                <th class="uk-text-center">{{Lang::get('common/general.actions')}}</th>
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
                },{
                    "orderable": false, "targets": [4]
                }]
            })
        }
    });
    </script>
@endsection
