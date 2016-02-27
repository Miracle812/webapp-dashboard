@extends('newlayout.base')
@section('title')
    @parent
    :: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')

    <div id="page_content">
        <div id="page_content_inner">

            <h2 class="heading_b uk-margin-bottom"><span class="uk-text-primary">Pods</span> Areas</h2>

            <div class="md-card">

                <div class="md-card-toolbar">

                    <h3 class="md-card-toolbar-heading-text large">Last Temperatures</h3>
                </div>

                <div class="md-card-content">
                    
                    <div class="uk-grid">
                        <div class="uk-width-1-1">
                            <table class="uk-table uk-table-striped uk-table-valign-middle dataTable" id="dataTable" data-source="/temperatures/datatable/pods/{{$range}}">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>Last Date</th>
                                    <th>Appliance</th>
                                    <th class="uk-text-center">Last temp.</th>
                                    <th class="uk-text-center">Voltage</th>
                                    {{--<th class="uk-text-right">Battery</th>--}}
                                    <th class="uk-text-center">Day Status</th>
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
                }]
            })
        }
    });
    </script>
@endsection