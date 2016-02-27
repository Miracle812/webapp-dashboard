@extends('_manager.layouts.manager')
@section('title')
    @parent
    :: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')
    <div class="m-b-md">
        <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>{{$sectionName}}</h3>
    </div>
    @include('breadcrumbs',['data' => $breadcrumbs])
    <div class="row">
        <div class="col-sm-12">
            <section class="panel panel-default">
                <header class="panel-heading">
                    {{$sectionName}} - {{$actionName}}
                </header>
                <div class="table-responsive">
                    <table class="table table-striped m-b-none dataTable small" id="dataTable" data-source="{{URL::to('/units/datatable')}}">
                        <thead>
                        <tr>
                            <th></th>
                            <th>{{Lang::get('common/general.created')}}</th>
                            <th>{{Lang::get('common/general.name')}}</th>
                            <th>{{Lang::get('common/general.identifier')}}</th>
                            <th>{{Lang::get('common/general.managers')}}</th>
                            <th>{{Lang::get('common/general.visitors')}}</th>
                            <th>{{Lang::get('common/general.logo')}}</th>
                            <th>{{Lang::get('common/general.status')}}</th>
                            <th>{{Lang::get('common/general.edit')}}</th>
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
