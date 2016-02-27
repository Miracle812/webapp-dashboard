@extends('_panel.layouts.panel')
@section('content')
<div class="m-b-md">
    <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i> <span class="h3 text-primary"> {{ucfirst($area->name)}}</span> Temperatures</h3>
</div>

<ul class="breadcrumb">
    <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
    <li><a href="{{URL::to('/temperatures')}}"><i class="fa fa-list-ul"></i> Temperatures</a></li>
    <li><a href="/temperatures/{{lcfirst($group->name)}}"><i class="fa fa-list-ul"></i> {{$group->name}} Areas</a></li>
    <li class="active">{{$area->name}} Temperatures</li>h
</ul>

<div class="row">
    <div class="col-lg-12">
        <section class="panel panel-default outstanding-task-dashboard">
            <header class="panel-heading">
                <div class="h4 col-sm-4">{{ucfirst($area->name)}} Temperatures List</div>
                <div class="col-sm-8 text-right">
                    <a href="<?=URL::to("/temperatures/$group/$area->identifier/last-100")?>" class="btn btn-green btn-xs @if($date_range_type=='last-100'||empty($date_range_type)) active @endif">Last 100</a>
                    <a href="<?=URL::to("/temperatures/$group/$area->identifier/today")?>" class="btn btn-green btn-xs @if($date_range_type=='today'||empty($date_range_type)) active @endif">Today</a>
                    <a href="<?=URL::to("/temperatures/$group/$area->identifier/this-week")?>" class="btn btn-green btn-xs @if($date_range_type=='this-week') active @endif">This Week</a>
                    <a href="<?=URL::to("/temperatures/$group/$area->identifier/this-month")?>" class="btn btn-green btn-xs @if($date_range_type=='this-month') active @endif">This Month</a>
                    <a href="<?=URL::to("/temperatures/$group/$area->identifier/last-month")?>" class="btn btn-green btn-xs @if($date_range_type=='last-month') active @endif">Last Month</a>
                    <a href="<?=URL::to("/temperatures/$group/$area->identifier/this-year")?>" class="btn btn-green btn-xs @if($date_range_type=='this-year') active @endif">This Year</a>
                </div>
                <i class="fa fa-info-sign text-muted" data-toggle="tooltip" data-placement="bottom" data-title="ajax to load the data."></i>
            </header>
            <div class="row">
                <div class="col-sm-12">
                    {{HTML::DatatableFilter()}}
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped m-b-none dataTable" id="dataTable" date-filter="true"  data-source="<?=URL::to("/temperatures/datatable/$group->identifier/$area->identifier/$date_range_type")?>">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Updated</th>
                        <th>Staff</th>
                        <th>Device</th>
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