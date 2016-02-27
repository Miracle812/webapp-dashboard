@extends('_panel.layouts.panel')
@section('title')
@parent
:: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')
<div class="m-b-md">
    <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>{{$sectionName}}
        <span class="pull-right">
           <a class="btn btn-green" href="{{URL::to("/forms-manager/create")}}"><i class="material-icons">add</i> {{Lang::get('common/button.create')}} </a>
        </span>
    </h3>
</div>
@include('breadcrumbs',['data' => $breadcrumbs])

<div class="row">
    <div class="col-sm-12">
        <section class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        {{HTML::DatatableFilter()}}
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped m-b-none dataTable small" id="dataTable" date-filter="true"  data-source="{{URL::to('/forms-manager/datatable')}}">
                        <thead>
                        <tr>
                            <th></th>
                            <th>{{\Lang::get('/common/general.created')}}</th>
                            <th>{{\Lang::get('/common/general.name')}}</th>
                            <th>{{\Lang::get('/common/general.assigned')}}</th>
                            <th>{{\Lang::get('/common/general.active')}}</th>
                            <th class="text-center" width="120px">{{\Lang::get('/common/general.action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <div class="clearfix"></div>
                </div>
            </div>
        </section>
    </div>
</div>

@endsection
@section('css')
    {{ Basset::show('package_datatables.css') }}
@endsection
@section('js')
    {{ Basset::show('package_datatables.js') }}
@endsection