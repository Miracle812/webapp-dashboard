@extends('_visitor.layouts.visitor')
@section('content')
<ul class="breadcrumb">
    <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
    <li>Food Incidents</li>
</ul>
<div class="row">
    <div class="col-lg-12">
        <section class="panel panel-default">
            <header class="panel-heading-navitas">
                Food Incidents Details
            </header>
            <div class="row">
                <div class="col-sm-12">
                    {{HTML::DatatableFilter()}}
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped m-b-none dataTable" id="dataTable" date-filter="true"  data-source="{{URL::to('/food-incidents/datatable')}}">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Created at</th>
                        <th>Unit</th>
                        <th>Food Compained of</th>
                        <th>Incident Details</th>
                        <th>Category</th>
                        <th>Complaint name</th>
                        <th>Status</th>
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