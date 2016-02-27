@extends('_manager.layouts.manager')
@section('title')
@parent
:: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')
<div class="m-b-md">
    <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>{{$sectionName}}
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
                <form class="form-horizontal" method="post">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label class="control-label">{{\Lang::get('/common/general.name')}}:</label>
                                    {{$unit->name}}
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label class="control-label">{{\Lang::get('/common/general.post_code')}}:</label>
                                    {{$unit->post_code}}
                                </div>

                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label class="control-label">{{\Lang::get('/common/general.city')}}:</label>
                                    {{$unit->city}}
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label class="control-label">{{\Lang::get('/common/general.street_number')}}:</label>
                                    {{$unit->street_number}}
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label class="control-label">{{\Lang::get('/common/general.phone')}}:</label>
                                    {{$unit->phone}}
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label class="control-label">{{\Lang::get('/common/general.email')}}:</label>
                                    {{$unit->email}}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <section
                                id="gmap_geocoding"
                                style="height:310px;"
                                class="m-b"
                                data-gmaplat="{{Input::old('gmap_lat', $unit->gmap_lat)}}"
                                data-gmaplng="{{Input::old('gmap_lng', $unit->gmap_lng)}}"
                                data-gmapzoom="{{Input::old('gmap_zoom', $unit->gmap_zoom)}}">
                            </section>

                            <input type="hidden" name="gmap_lat" value="{{Input::old('gmap_lat', $unit->gmap_lat)}}">
                            <input type="hidden" name="gmap_lng" value="{{Input::old('gmap_lng', $unit->gmap_lng)}}">
                            <input type="hidden" name="gmap_zoom" value="{{Input::old('gmap_zoom', $unit->gmap_zoom)}}">
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>
</div>
@endsection
@section('js')
    {{ Basset::show('package_googlemap.js') }}
@endsection