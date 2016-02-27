@extends('_visitor.layouts.visitor')
@section('title')
    @parent
    :: {{$sectionName}}
@endsection
@section('content')
    <div class="m-b-md">
        <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>{{$sectionName}}
            <span class="pull-right">
            <a class="btn btn-green" href="{{URL::to("/health-questionnaires/submitted-list")}}"><i class="fa fa-search"></i> {{Lang::get('common/general.datatable')}} </a>
        </span>
        </h3>
    </div>
    @include('breadcrumbs',['data' => $breadcrumbs])
    <div class="row">
        <div class="col-lg-12">
            <section class="panel panel-default">
                <div class="row">
                    <div class="col-sm-12">
                        {{HTML::DatatableFilter()}}
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped m-b-none dataTable" id="dataTable" date-filter="true"  data-source="{{URL::to("/health-questionnaires/staff-datatable/'.$staff->id")}}">
                        <thead>
                        <tr>
                            <th></th>
                            <th>{{Lang::get('common/general.created')}}</th>
                            <th>{{Lang::get('common/sections.staff.title')}}</th>
                            <th>{{Lang::get('common/general.form_name')}}</th>
                            <th class="text-center">{{Lang::get('common/general.actions')}}</th>
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