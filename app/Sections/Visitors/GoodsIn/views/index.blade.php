@extends('_visitor.layouts.visitor')
@section('content')
<ul class="breadcrumb">
    <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
    <li>Goods In</li>
</ul>
<div class="row">
    <div class="col-lg-12">
        <section class="panel panel-default">
            <header class="panel-heading-navitas">
                Goods In
            </header>
            <div class="row">
                <div class="col-sm-12">
                    {{HTML::DatatableFilter()}}
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped m-b-none dataTable text-xs" id="dataTable" date-filter="true"  data-source="{{URL::to('/goods-in/datatable')}}">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Created</th>
                        <th>Unit</th>
                        <th>Probe</th>
                        <th>Staff</th>
                        <th>Supplier</th>
                        <th>Products</th>
                        <th>Temp.</th>
                        <th>Invoice</th>
                        <th>Package</th>
                        <th>Data Code</th>
                        <th>Compliant</th>
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