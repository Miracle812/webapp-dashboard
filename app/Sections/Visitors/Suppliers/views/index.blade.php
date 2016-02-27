@extends('_visitor.layouts.visitor')
@section('title')
    @parent
    :: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')
    <div class="m-b-md">
        <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>{{$sectionName}}
            <span class="pull-right">
              <a class="btn btn-green" href="{{URL::to('/goods-in/deliveries')}}"><i class="fa fa-list"></i> {{Lang::get('common/button.suppliers_deliveries')}} </a>
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
                <div class="row">
                    <div class="col-sm-12">
                        {{HTML::DatatableFilter()}}
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped m-b-none dataTable" id="dataTable" date-filter="true"  data-source="{{URL::to("/suppliers/datatable")}}">
                        <thead>
                            <tr>
                                <th></th>
                                <th width="45">{{Lang::get('common/general.created')}}</th>
                                <th>{{Lang::get('common/general.name')}}</th>
                                <th>{{Lang::get('common/general.city')}}</th>
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
@endsection
@section('css')
    {{ Basset::show('package_datatables.css') }}
@endsection