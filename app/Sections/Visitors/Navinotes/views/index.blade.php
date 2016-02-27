@extends('_visitor.layouts.visitor')
@section('title')
@parent
:: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')
<div class="m-b-md">
    <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>{{$sectionName}}
        <span class="pull-right">
        </span>
    </h3>
</div>
@include('breadcrumbs',['data' => $breadcrumbs])
<div class="row">
    <div class="col-sm-12">
        <section class="panel panel-default">
            <div class="row">
                <div class="col-sm-12">
                    {{HTML::DatatableFilter()}}
                </div>
            </div>
            <header class="panel-heading">
                {{$sectionName}} - {{$actionName}}
            </header>
            <div class="table-responsive">
                <table class="table table-striped m-b-none dataTable small" id="dataTable" date-filter="true"  data-source="{{URL::to('/navinotes/datatable')}}">
                    <thead>
                        <tr>
                            <th></th>
                            <th>{{Lang::get('common/general.created_at')}}</th>

                            <th>{{Lang::get('common/general.name')}}</th>
                            <th>{{Lang::get('common/general.description')}}</th>
                            <th>{{Lang::get('common/general.files')}}</th>
                            <th class="text-center" width="100px">{{Lang::get('common/general.details')}}</th>
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