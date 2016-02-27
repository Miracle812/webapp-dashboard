@extends('_admin.layouts.admin')
@section('title')
@parent
:: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')
<div class="m-b-md">
    <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>{{$sectionName}}
        <span class="pull-right">
           <a class="btn btn-green inline" href="{{URL::to('/auto-messages/groups/create')}}"><i class="fa fa-plus"></i> {{Lang::get('common/button.create-group')}} </a>
        </span>
    </h3>
</div>
@include('breadcrumbs',['data' => $breadcrumbs])
<div class="row">
    <div class="col-sm-12">
        <section class="panel panel-default">
            <header class="panel-heading">
                {{$sectionName}} - {{$actionName}}
            </header>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped m-b-none dataTable small" id="dataTable" date-filter="true"  data-source="{{URL::to('/auto-messages/groups/datatable')}}">
                        <thead>
                        <tr>
                            <th></th>
                            <th>{{\Lang::get('/common/general.created')}}</th>
                            <th>{{\Lang::get('/common/general.name')}}</th>
                            <th>{{\Lang::get('/common/general.type')}}</th>
                            <th>{{\Lang::get('/common/general.on_email')}}</th>
                            <th>{{\Lang::get('/common/general.on_sms')}}</th>
                            <th>{{\Lang::get('/common/general.messages')}}</th>
                            <th>{{\Lang::get('/common/general.active')}}</th>
                            <th>{{\Lang::get('/common/general.action')}}</th>
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
@section('js')
    {{ Basset::show('package_datatables.js') }}
@endsection
@section('css')
    {{ Basset::show('package_datatables.css') }}
@endsection