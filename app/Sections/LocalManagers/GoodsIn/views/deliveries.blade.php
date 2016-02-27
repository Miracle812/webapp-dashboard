@extends('newlayout.base')
@section('title')
    @parent
    :: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')

    <div id="page_content">
        <div id="page_content_inner">

            <h2 class="heading_b uk-margin-bottom clearfix">{{$sectionName}}</h2>

            <?php /*@include('breadcrumbs',['data' => $breadcrumbs]) */?>

            <div class="md-card">

                <div class="md-card-toolbar">
                    <h3 class="md-card-toolbar-heading-text large">
                        {{$sectionName}} - {{$actionName}}
                    </h3>
                </div>

                <div class="md-card-content">

                    <div class="uk-grid">
                        <div class="uk-width-1-1">

                            <table class="uk-table dataTable uk-table-valign-middle" id="dataTable" data-source="{{URL::to('/goods-in/deliveries-datatable')}}">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th width="45">{{Lang::get('common/general.submitted')}}</th>
                                    <th>{{Lang::get('common/general.name')}}</th>
                                    <th class="text-center">{{Lang::get('common/general.products')}}</th>
                                    <th class="text-center">{{Lang::get('common/general.temperature')}}</th>
                                    <th class="text-center">{{Lang::get('common/general.date_code_valid')}}</th>
                                    <th class="text-center">{{Lang::get('common/general.package_accept')}}</th>
                                    <th class="text-center">{{Lang::get('common/general.compliant')}}</th>
                                    <th class="text-center">{{Lang::get('common/general.action')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>

                        </div>
                    </div>

                </div>
            </div>
            
        </div>
    </div>
@endsection
@section('styles')
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
                    "orderable": false, "targets": [5,6,7,8]
                }]
            })
        }
    });
    </script>
@endsection