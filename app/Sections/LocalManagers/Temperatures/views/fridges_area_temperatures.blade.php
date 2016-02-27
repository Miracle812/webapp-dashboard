@extends('_panel.layouts.panel')
@section('content')
<div class="m-b-md">
    <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i> <span class="h3 text-primary">{{$item->name}}</span> Temperatures</h3>
</div>
<ul class="breadcrumb">
    <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
    <li><a href="{{URL::to('/temperatures/fridges')}}"><i class="fa fa-list-ul"></i>Fridges Temperatures</a></li>
    <li><i class="fa fa-list-ul"></i> {{$item->name}} Temperatures</li>
</ul>

<div class="row">
    <div class="col-lg-12">
        <section class="panel panel-default outstanding-task-dashboard">
            <header class="panel-heading-navitas">
                    <div class="btn btn-green " data-toggle="ajaxModal" data-remote="/temperatures/details/{{$group}}/{{$id_area}}"><i class="i i-fw i-stats m-r"></i>Show Statistics</div>
                    <div class="pull-right">
                        <a href="{{URL::to("/temperatures/$group/area/$id_area/last-100")}}" class="btn btn-default @if($date_range_type=='last-100'||empty($date_range_type)) active @endif">Last 100</a>
                        <a href="{{URL::to("/temperatures/$group/area/$id_area/today")}}" class="btn btn-default @if($date_range_type=='today'||empty($date_range_type)) active @endif">Today</a>
                        <a href="{{URL::to("/temperatures/$group/area/$id_area/this-week")}}" class="btn btn-default @if($date_range_type=='this-week') active @endif">This Week</a>
                        <a href="{{URL::to("/temperatures/$group/area/$id_area/this-month")}}" class="btn btn-default @if($date_range_type=='this-month') active @endif">This Month</a>
                        <a href="{{URL::to("/temperatures/$group/area/$id_area/last-month")}}" class="btn btn-default @if($date_range_type=='last-month') active @endif">Last Month</a>
                        <a href="{{URL::to("/temperatures/$group/area/$id_area/this-year")}}" class="btn btn-default @if($date_range_type=='this-year') active @endif">This Year</a>
                    </div>
                <i class="fa fa-info-sign text-muted" data-toggle="tooltip" data-placement="bottom" data-title="ajax to load the data."></i>
            </header>
            <div class="row">
                <div class="col-sm-12">
                    {{HTML::DatatableFilter()}}
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped m-b-none dataTable" id="dataTable" date-filter="true"  data-source="/temperatures/datatable/{{$group}}/{{$id_area}}/{{$date_range_type}}">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Updated</th>
                        <?php if($group=='probes'): ?><th>Device Name</th><?php endif; ?>
                        <th>Area</th>
                        <th>Item</th>
                        <th>Temp</th>
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