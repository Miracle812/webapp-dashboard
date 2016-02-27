@extends('_visitor.layouts.visitor')
@section('title')
    @parent
    :: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')
    <div class="m-b-md">
        <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>{{$sectionName}} :: {{$actionName}}
            <span class="pull-right">
        </span>
        </h3>
    </div>
    @include('breadcrumbs',['data' => $breadcrumbs])
    <div class="row">
        <div class="col-lg-12">
            <section class="panel panel-default">
                <div class="table-responsive">
                    <table class="table table-striped m-b-none dataTable" id="dataTable" date-filter="true"  data-source="{{URL::to('/trainings/datatable/'.$staff->id)}}">
                        <thead>
                        <tr>
                            <th></th>
                            <th>{{Lang::get('common/general.created')}}</th>
                            <th>{{Lang::get('common/sections.staff.title')}}</th>
                            <th>{{Lang::get('common/general.name')}}</th>
                            <th>{{Lang::get('common/general.date_start')}}</th>
                            <th>{{Lang::get('common/general.date_finish')}}</th>
                            <th>{{Lang::get('common/general.date_refresh')}}</th>
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