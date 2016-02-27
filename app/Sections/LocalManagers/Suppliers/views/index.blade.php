@extends('newlayout.base')
@section('title')
    @parent
    :: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')

    <div id="page_content">
        <div id="page_content_inner">

            <h2 class="heading_b uk-margin-bottom">{{$sectionName}}
                <span class="panel-action">
                    <a class="md-btn md-bg-green-600 md-color-white md-btn-wave-light waves-effect waves-button waves-light" href="{{URL::to("/suppliers/create")}}"><i class="material-icons">add</i> {{Lang::get('common/button.create')}} </a>
                    <a class="md-btn md-bg-green-600 md-color-white md-btn-wave-light waves-effect waves-button waves-light" href="{{URL::to("/suppliers/import")}}"><i class="material-icons">add</i> {{Lang::get('common/button.import')}} </a>
                    <a class="md-btn md-bg-green-600 md-color-white md-btn-wave-light waves-effect waves-button waves-light" href="{{URL::to('/goods-in/deliveries')}}"><i class="material-icons">list</i> {{Lang::get('common/button.suppliers_deliveries')}} </a>
                </span>
            </h2>

            <?php /*@include('breadcrumbs',['data' => $breadcrumbs]) */?>

            <div class="md-card">

                <div class="md-card-toolbar">
                    <h3 class="md-card-toolbar-heading-text large">
                        {{$sectionName}} - {{$actionName}}
                    </h3>
                </div>

                <div class="md-card-content">

                    <table class="uk-table uk-table-striped dataTable" id="dataTable" data-source="{{URL::to('/suppliers/datatable')}}">
                        <thead>
                        <tr>
                            <th></th>
                            <th>{{Lang::get('common/general.name')}}</th>
                            <th>{{Lang::get('common/general.city')}}</th>
                            <th class="uk-text-center">{{Lang::get('common/general.details')}}</th>
                            <th class="uk-text-center">{{Lang::get('common/general.logo')}}</th>
                            <th class="uk-text-center">{{Lang::get('common/general.action')}}</th>
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
                    "orderable": false, "targets": [3, 4, 5]
                }]
            })
        }
    });
    </script>
@endsection