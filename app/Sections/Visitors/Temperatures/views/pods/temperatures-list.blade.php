@extends('_visitor.layouts.visitor')
@section('content')
<div class="m-b-md">
    <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i> <span class="h3 text-primary"> {{ucfirst($area->name)}}</span> Temperatures
        <span class="pull-right">
            <?php $currFilter = '?filter=invalid'?>
            @if(Request::get('filter')=='invalid')
                <?php $currFilter = $currFilter?>
                <?php $dataLink = [URL::current(), '', 'All temperatures','success']?>
            @else
                <?php $dataLink = [URL::current(), $currFilter, 'Invalid Temperatures','danger']?>
                <?php $currFilter = ''?>
            @endif
            <a href="{{$dataLink[0].$dataLink[1]}}" class="btn btn-{{$dataLink[3]}} ">{{$dataLink[2]}}</a>
        </span>
    </h3>
</div>

<ul class="breadcrumb">
    <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
    <li><a href="{{URL::to('/temperatures/')}}"><i class="fa fa-list-ul"></i> Temperatures</a></li>
    <li><a href="/temperatures/pods"><i class="fa fa-list-ul"></i> Pods Areas</a></li>
    <li class="active">{{$area->name}} Temperatures</li>
</ul>
@if($area->getNavitasMessage())
<div class="row">
    <div class="col-sm-12">
        <section class="panel panel-default">
            <header class="panel-heading">
                <span class="h3 text-primary">{{$area->getNavitasMessage()}}</span>
            </header>
        </section>
    </div>
</div>
@endif
<div class="row">
    <div class="col-lg-12">
        <section class="panel panel-default outstanding-task-dashboard">
            <header class="panel-heading">
                <div class="h4 col-sm-4">{{ucfirst($area->name)}}</div>
                <div class="col-sm-8 text-right">
                    <a href="<?=URL::to("/temperatures/pods/$area->id/last-100")?>{{$currFilter}}" class="btn btn-green btn-xs @if($range=='last-100'||empty($range)) active @endif">Last 100</a>
                    <a href="<?=URL::to("/temperatures/pods/$area->id/today")?>{{$currFilter}}" class="btn btn-green btn-xs @if($range=='today'||empty($range)) active @endif">Today</a>
                    <a href="<?=URL::to("/temperatures/pods/$area->id/this-week")?>{{$currFilter}}" class="btn btn-green btn-xs @if($range=='this-week') active @endif">This Week</a>
                    <a href="<?=URL::to("/temperatures/pods/$area->id/this-month")?>{{$currFilter}}" class="btn btn-green btn-xs @if($range=='this-month') active @endif">This Month</a>
                    <a href="<?=URL::to("/temperatures/pods/$area->id/last-month")?>{{$currFilter}}" class="btn btn-green btn-xs @if($range=='last-month') active @endif">Last Month</a>
                    <a href="<?=URL::to("/temperatures/pods/$area->id/this-year")?>{{$currFilter}}" class="btn btn-green btn-xs @if($range=='this-year') active @endif">This Year</a>
                </div>
                <i class="fa fa-info-sign text-muted" data-toggle="tooltip" data-placement="bottom" data-title="ajax to load the data."></i>
            </header>
            <div class="row">
                <div class="col-sm-12">
                    {{HTML::DatatableFilter()}}
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped m-b-none dataTable" id="dataTable" date-filter="true"  data-source="<?=URL::to("/temperatures/datatable/pods/$area->id/$range")?>{{$currFilter}}">
                    <thead>
                    <tr>
                        <th></th>
                        <th class="text-center">Created</th>
                        <th class="text-center">Device</th>
                        <th class="text-center">Temperature</th>
                        <th class="text-center">Voltage</th>
                        {{--<th class="text-center">Battery</th>--}}
                        <th class="text-center">Status</th>
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