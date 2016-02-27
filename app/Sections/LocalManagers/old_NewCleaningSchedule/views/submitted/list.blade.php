@extends('_panel.layouts.panel')
@section('title')
    @parent
    :: {{$sectionName}}
@endsection
@section('content')
    <div class="m-b-md">
        <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>{{$sectionName}}
            <span class="pull-right">
                <a class="btn btn-primary" href="{{URL::to("/new-cleaning-schedule/tasks-list")}}"><i class="material-icons">list</i> Tasks list</a>
                <a class="btn btn-primary" href="{{URL::to("/new-cleaning-schedule/forms")}}"><i class="material-icons">list</i> Forms tasks</a>
                <a class="btn btn-green" href="{{URL::to("/new-cleaning-schedule")}}"><i class="material-icons">add</i> {{Lang::get('common/button.calendar')}} </a>
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
                    <table class="table table-striped m-b-none dataTable" id="dataTable" date-filter="true"  data-source="{{URL::to("/new-cleaning-schedule/datatable")}}">
                        <thead>
                        <tr>
                            <th></th>
                            <th>{{Lang::get('common/general.created')}}</th>
                            <th>{{Lang::get('common/general.task_name')}}</th>
                            <th>{{Lang::get('common/general.task_date')}}</th>
                            <th>{{Lang::get('common/general.completed')}}?</th>
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