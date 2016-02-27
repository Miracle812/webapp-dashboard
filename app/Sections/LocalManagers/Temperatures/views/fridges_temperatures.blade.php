@extends('_panel.layouts.panel')
@section('content')
<div class="m-b-md">
    <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i> <span class="h3 text-primary">Fridges</span> Temperatures</h3>
</div>
<ul class="breadcrumb">
    <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
    <li><a href="{{URL::to('/temperatures')}}"><i class="fa fa-list-ul"></i> Temperatures</a></li>
    <li><i class="fa fa-list-ul"></i> Fridges Areas</li>
</ul>
<div class="row">
    <div class="col-lg-12">
        <section class="panel panel-default outstanding-task-dashboard">
            <header class="panel-heading-navitas">
                Fridges Areas - Last Temperatures
            </header>
            <div class="row">
                <div class="col-sm-12">
                    {{HTML::DatatableFilter()}}
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped m-b-none dataTable" id="dataTable" date-filter="true"  data-source="/temperatures/datatable/{{$group}}/{{$date_range_type}}">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Updated</th>
                        <th>Area</th>
                        <th>Last temp.</th>
                        <th>Item</th>
                        <th>Status</th>
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
@endsection
@section('css')
{{ Basset::show('package_datatables.css') }}
@endsection