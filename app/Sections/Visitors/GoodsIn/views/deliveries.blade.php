@extends('_visitor.layouts.visitor')
@section('title')
    @parent
    :: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')
    <div class="m-b-md">
        <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>{{$sectionName}}
        </h3>
    </div>
    @include('breadcrumbs',['data' => $breadcrumbs])
    <div class="row">
        <div class="col-sm-12">
            <section class="panel panel-default">
                <header class="panel-heading">
                    {{$sectionName}} - {{$actionName}}
                </header>

                <?php $htmlFilter = '<div class="form-group m-l">
                    <label class="control-label">' . Lang::get("common/general.suppliers") . ': </label>' . (Form::select('datatable[supplier_id]', (['select' => 'Select Supplier:'] + $suppliers), Input::old('datatable.supplier_id', null), array('class' => 'form-control', 'id' => "id"))) . '</div>';
                ?>

                <div class="row">
                    <div class="col-sm-12">
                        {{HTML::DatatableFilter($htmlFilter)}}
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped m-b-none dataTable" id="dataTable" date-filter="true"
                           data-source="{{URL::to('/goods-in/in-records-datatable')}}">
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
                            <th class="text-center">{{Lang::get('common/general.details')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <div class="clearfix"></div>
                </div>
            </section>
        </div>
    </div>
@endsection
@section('js')
    {{ Basset::show('package_datatables.js') }}
    {{ Basset::show('package_datetimepicker.js') }}
    <script>
        $(".datetimepicker").datetimepicker({
            format: 'YYYY-MM-DD',
            pickTime: false,
            icons: {
                time: "fa fa-clock-o",
                date: "fa fa-calendar",
                up: "fa fa-arrow-up",
                down: "fa fa-arrow-down"
            }
        });
        $('form').on('submit', function (e) {
            if (e.handled == 1) {
                e.handled = 1;
                return false;
            }
            e.preventDefault();
            var id = $("#id").val();
            var from = $("#date_from").val();
            var to = $("#date_to").val();
            var url = $('#dataTable').data('source');
            var table = $('#dataTable').DataTable({
                "aoColumnDefs": [
                    {"iDataSort": 0, "aTargets": [1]},
                    {"bVisible": false, "aTargets": [0]}
                ],
                "bAutoWidth": false,
                "order": [[1, "desc"]],
                "bDestroy": true,
                ajax: url + '?' + $.param({id: id, date_from: from, date_to: to})
            });
        });
    </script>
@endsection
@section('css')
    {{ Basset::show('package_datatables.css') }}
    {{ Basset::show('package_datetimepicker.css') }}
@endsection

