@extends('_visitor.layouts.visitor')
@section('title')
    @parent
    :: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')
    <div class="m-b-md">
        <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>{{$sectionName}}
            <span class="pull-right">
            <a class="btn btn-green" href="{{URL::to('/suppliers')}}"><i class="fa fa-search"></i> {{Lang::get('common/button.list')}} </a>
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
                    <div class="row m-t b-b">
                        <div class="col-lg-5 col-xs-12">
                            @if($supplier->logo)
                                <div class="col-xs-5 col-lg-5">
                                    <div class="thumbnail">
                                        <img src="{{$supplier->logo}}" width="100%">
                                    </div>
                                </div>
                            @endif
                            <div class="col-xs-5 col-sm-5 col-lg-7">
                                <ul class="list-unstyled">
                                    <li class="h4 text-primary font-bold m-b">{{$supplier->name}}</li>
                                    <li id="GMapAddrres1"><i class="fa fa-home m-r text-primary"></i>{{$supplier->post_code}} {{$supplier->city}}</li>
                                    <li id="GMapAddrres2"><i class="fa fa-road m-r text-primary"></i>{{$supplier->street_number}}</li>
                                    <li><i class="fa fa-phone m-r text-primary"></i>{{$supplier->phone}}</li>
                                    <li><i class="i i-mail2 m-r text-primary"></i>{{$supplier->email}}</li>
                                    <li><i class="fa fa-user m-r text-primary"></i>{{$supplier->contact_person}}</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-7 col-xs-12 thumbnail">
                            <section
                                    id="gmap_geocoding"
                                    style="height:200px;"
                                    data-gmaplat="{{$supplier->gmap_lat}}"
                                    data-gmaplng="{{$supplier->gmap_lng}}"
                                    data-gmapzoom="{{$supplier->gmap_zoom}}">
                            </section>
                        </div>
                    </div>
                    <div class="row m-t panel-heading thumbnail m b-b">
                        <div class="row">
                            <div class="col-sm-5 ">
                                <label class="text-primary font-bold h4 m-b">{{Lang::get('common/general.supplied_products')}}:</label>
                                <h4 class="text-navitas">{{$supplier->toString($supplier->getProducts())}}</h4>
                                <div class=" ">
                                    <div class="h4 text-primary font-bold">{{\Lang::get('/common/general.rule_temperatures')}}:</div>
                                    <div class="col-sm-6 no-padder">
                                        <div class="m-t">
                                            <label class="control-label">{{\Lang::get('/common/general.valid_min')}}</label>
                                            <h4 class="text-primary">{{$supplier->valid_min}} &#x2103</h4>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 no-padder">
                                        <div class="m-t">
                                            <label class="control-label">{{\Lang::get('/common/general.valid_max')}}</label>
                                            <h4 class="text-primary">{{$supplier->valid_max}} &#x2103</h4>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 no-padder">
                                        <div class="m-t">
                                            <label class="control-label">{{\Lang::get('/common/general.warning_min')}} (below)</label>
                                            <h4 class="text-primary">{{$supplier->warning_min}} &#x2103</h4>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 no-padder">
                                        <div class="m-t">
                                            <label class="control-label">{{\Lang::get('/common/general.warning_max')}} (above)</label>
                                            <h4 class="text-primary">{{$supplier->warning_max}} &#x2103</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-7  ">
                                <label class="text-primary font-bold h4 m-b">{{Lang::get('common/general.additional_contacts')}}:</label>
                                <div class="row">
                                    <div class="col-sm-6">
                                        @if($supplier->contact_person1)
                                            <ul class="list-unstyled">
                                                <li><i class="fa fa-user m-r text-primary"></i><span class="font-bold text-primary">{{$supplier->contact_person1}}</span></li>
                                                <li><i class="fa fa-phone m-r text-primary"></i>{{$supplier->phone1}}</li>
                                                <li><i class="i i-mail2 m-r text-primary"></i>{{$supplier->email2}}</li>
                                            </ul>
                                        @endif
                                    </div>
                                    <div class="col-sm-6">
                                        @if($supplier->contact_person2)
                                            <ul class="list-unstyled">
                                                <li><i class="fa fa-user m-r text-primary"></i><span class="font-bold text-primary">{{$supplier->contact_person1}}</span></li>
                                                <li><i class="fa fa-phone m-r text-primary"></i>{{$supplier->phone1}}</li>
                                                <li><i class="i i-mail2 m-r text-primary"></i>{{$supplier->email2}}</li>
                                            </ul>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <header class="panel-heading">
                    <span class="text-danger font-bold">{{Lang::get('common/sections.goods-in.title')}}</span>
                </header>
                <div class="panel-body">
                    <div class="row">
                        <div class="row">
                            <div class="col-sm-12">
                                {{HTML::DatatableFilter()}}
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped m-b-none dataTable small" id="dataTable" date-filter="true"  data-source="{{URL::to('/goods-in/in-suppliers-datatable/'.$supplier->id)}}">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>{{Lang::get('common/general.created')}}</th>
                                    <th>{{Lang::get('common/general.staff')}}</th>
                                    <th>{{Lang::get('common/general.products')}}</th>
                                    <th>{{Lang::get('common/general.temperature')}}</th>
                                    <th>{{Lang::get('common/general.package_accept')}}</th>
                                    <th>{{Lang::get('common/general.date_code_valid')}}</th>
                                    <th>{{Lang::get('common/general.compliant')}}</th>
                                    <th>{{Lang::get('common/general.details')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
@section('js')
    {{ Basset::show('package_datatables.js') }}
    {{ Basset::show('package_googlemap.js') }}
@endsection
@section('css')
    {{ Basset::show('package_datatables.css') }}
@endsection