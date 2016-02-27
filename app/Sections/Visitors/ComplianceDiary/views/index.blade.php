@extends('_visitor.layouts.visitor')
@section('content')
    <ul class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li>Compliance Diary</li>
    </ul>
    <div class="row">
        <div class="col-lg-12">
            <section class="panel panel-default">
                <header class="panel-heading-navitas">
                    Compliance Diary
                </header>
                <div class="row">
                    <div class="col-sm-12">
                        {{HTML::DatatableFilter()}}
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped m-b-none dataTable text-xs" id="dataTable" date-filter="true"  data-source="{{URL::to('/compliance-diary/datatable')}}">
                        <thead>
                        <tr>
                            <th></th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Type</th>
                            <th>Repeat</th>
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