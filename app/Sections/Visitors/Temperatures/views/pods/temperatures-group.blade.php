@extends('_visitor.layouts.visitor')
@section('content')
<div class="m-b-md">
    <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i> <span class="h3 text-primary">Pods</span> Areas</h3>
</div>
<ul class="breadcrumb">
    <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="{{URL::to('/temperatures/')}}"><i class="fa fa-list-ul"></i> Temperatures</a></li>
    <li class="active">Pods Areas</li>
</ul>
<div class="row">
    <div class="col-lg-12">
        <section class="panel panel-default outstanding-task-dashboard">
            <header class="panel-heading-navitas">
                Last Temperatures
            </header>
            <div class="row">
                <div class="col-sm-12">
                    {{HTML::DatatableFilter()}}
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped m-b-none dataTable" id="dataTable" date-filter="true"  data-source="/temperatures/datatable/pods/{{$range}}">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Last Date</th>
                        <th>Appliance</th>
                        <th class="text-right">Last temp.</th>
                        <th class="text-right">Voltage</th>
                        {{--<th class="text-right">Battery</th>--}}
                        <th>Day Status</th>
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