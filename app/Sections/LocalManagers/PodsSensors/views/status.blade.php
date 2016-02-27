@extends('_panel.layouts.panel')
@section('title')
    @parent
    :: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')
<div class="m-b-md">
    <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>{{$sectionName}}
        <span class="pull-right">
            <a href="" class="btn btn-sm btn-success"><i class="fa fa-refresh text"></i> Refresh table</a>
        </span>
    </h3>
</div>
@include('breadcrumbs',['data' => $breadcrumbs])
<?php
$hub     = $pod -> getHub(); //hub from last pod temperature
$online  = ($hub && $hub -> status ) ? true : false ;

$lastHub = $pod -> getLastHub(); //last hub for pods temperature

$area    = $pod -> area;
$group   = $area -> group;

$temperature = $pod -> getLastTemperature();
?>

<section class="panel panel-default">

    <div class="row wrapper">   </div>
    <div class="table-responsive">
        <table class="table table-striped b-t b-light">
            <tbody>
            <thead><tr> <th></th><th colspan="3"><h4 class="font-bold text-primary">Server</h4></th></tr></thead>
            <tr>
                <td class="text-center">
                    @if($socket)
                        <span><i class="fa fa-check text-success"></i></span>
                    @else
                        <span><i class="fa fa-times text-danger"></i></span>
                    @endif
                </td>
                <td>Status</td>
                <td class="font-bold">@if($socket) <span class="text-success font-bold">Online @else <span class="text-danger font-bold">Offline @endif </span> </td>
                <td></td>
            </tr>
            <thead><tr> <th></th><th colspan="3"><h4 class="font-bold text-primary">Pod Sensor</h4></th></tr></thead>
            <tr>
                <td class="text-center">
                </td>
                <td>Group / Area</td>
                <td class="font-bold">{{ucfirst($group->identifier)}} / {{$area->name}}</td>
                <td></td>
            </tr>

            <tr>
                <td class="text-center">
                </td>
                <td>Identifier</td>
                <td class="font-bold">{{$pod->identifier}}</td>
                <td></td>
            </tr>

            <tr>
                <td class="text-center">
                </td>
                <td>Name</td>
                <td class="font-bold">{{$pod -> name}}</td>
                <td></td>
            </tr>

            <tr>
                <td class="text-center">
                </td>
                <td>Description</td>
                <td colspan="2" class="font-bold">{{$pod -> description}}</td>
            </tr>

            @if($temperature)
            <tr>
                <td class="text-center">
                </td>
                <td>Last temperature</td>
                <td colspan="2" class="font-bold"> {{$temperature -> temperature}} &#8451 at {{$temperature -> created_at()}} <br> {{$temperature->hub->ip}} </span>
                </td>
            </tr>
            @endif

<?php if($lastHub): ?>

            <thead><tr> <th></th><th colspan="3"><h4 class="font-bold text-primary">Hub</h4></th></tr></thead>
            <tr>
                <td class="text-center">
                    @if($lastHub->status)
                        <span><i class="fa fa-check text-success"></i></span>
                    @else
                        <span><i class="fa fa-times text-danger"></i></span>
                    @endif
                </td>
                <td>Status</td>
                <td><span class="@if($lastHub->status) text-success @else text-danger @endif">
                        @if($lastHub->status) <b> Online </b>
                        @else <b>Offline</b>
                        @endif
                    </span>
                </td>
                </td>
            </tr>
            <tr>
                <td class="text-center">
                </td>
                <td>Identifier</td>
                <td class="font-bold">{{$lastHub -> identifier}}</td>
                <td></td>
            </tr>
            <tr class="b-t">
                <td class="text-center">
                </td>
                <td>Last IP</td>
                <td class="font-bold">{{$lastHub -> ip}}</td>
                <td></td>
            </tr>
            @if($lastTemp = $lastHub->getLastTemperature())
                <tr>
                    <td class="text-center">
                    </td>
                    <td>Last temperature</td>
                    <td colspan="2" class="font-bold"> {{$lastTemp -> temperature}} &#8451 at {{$lastTemp -> created_at()}} </span>
                    </td>
                </tr>
            @endif
            <thead><tr> <th></th><th colspan="3"><h4 class="font-bold text-primary">Temperature rules</h4></th></tr></thead>
            <tr>
                <td class="text-center">
                </td>
                <td>Danger Range</td>
                <td class="font-bold">Below: {{$area -> warning_min}}, Above: {{$area -> warning_max}} &#8451</td>
                <td></td>
            </tr>

    <?php if($lastHub->status): ?>

            <tr>
                <td class="text-center"></td>
                <td>Update emperature rules from current area</td>
                <td class="font-bold">Min: {{$pod -> alert_min}}, Max: {{$pod -> alert_max}} &#8451</td>
                <td><a href="{{URL::to('/pods/sensors/set-alarm/'.$pod->id)}}" class="btn btn-sm btn-danger"><i class="fa fa-refresh text"></i> Update Alert Temperatures</a></td>
            </tr>

            <thead><tr> <th></th><th colspan="3"><h4 class="font-bold text-primary">Hub & Pod Settings</h4></th></tr></thead>

            <tr>
                <td class="text-center"></td>
                <td>Update emperature rules from current area</td>
                <td class="font-bold">Min: {{$pod -> alert_min}}, Max: {{$pod -> alert_max}} &#8451</td>
                <td><a href="{{URL::to('/pods/sensors/set-alarm/'.$pod->id)}}" class="btn btn-sm btn-danger"><i class="fa fa-refresh text"></i> Update Alert Temperatures</a></td>
            </tr>

            <tr>
                <td colspan="4">
                    <?php $code = '60';  ?>
                    <form role = "form" id="form_{{$code}}" class="form-inline" method="POST" action="{{URL::to('/pods/sensors/request/'.$code.'/pod/'.$pod->id)}}">

                        <div class="form-group col-sm-12">
                            <label class="font-bold text-primary"> Get temperatures from date range from current pod.</label>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group col-sm-4">
                                <input type="text" name="date_from[{{$code}}]" placeholder="Date from" class="form-control datetimepicker" value="{{Input::old('date_from.'.$code.'', null)}}">
                                @if($errors->has('date_from.'.$code.''))
                                    <div class="text-danger">{{ Lang::get($errors->first('date_from.'.$code.'')) }}</div>
                                @endif
                            </div>
                            <div class="form-group col-sm-4">
                                <input type="text" name="date_to[{{$code}}]" placeholder="Date to" class="form-control datetimepicker" value="{{Input::old('date_to.'.$code.'', null)}}">
                                @if($errors->has('date_to.'.$code.''))
                                    <div class="text-danger">{{ Lang::get($errors->first('date_to.'.$code.'')) }}</div>
                                @endif
                            </div>
                            <div class="form-group col-sm-4">
                                <button class="btn btn-success" type="submit">Get</button>
                            </div>
                        </div>
                    </form>
                </td>
            </tr>

            <tr>
                <td colspan="4">
                    <?php $code = '61';?>
                    <form role = "form" id="form_{{$code}}" class="form-inline" method="POST" action="{{URL::to('/pods/sensors/request/'.$code.'/hub/'.$hub->id)}}">

                        <div class="form-group col-sm-12">
                            <label class="font-bold text-primary"> Get temperatures from date range from all pods of current hub..</label>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group col-sm-4">
                                <input type="text" name="date_from[{{$code}}]" placeholder="Date from" class="form-control datetimepicker" value="{{Input::old('date_from.'.$code.'', null)}}">
                                @if($errors->has('date_from.'.$code.''))
                                    <div class="text-danger">{{ Lang::get($errors->first('date_from.'.$code.'')) }}</div>
                                @endif
                            </div>
                            <div class="form-group col-sm-4">
                                <input type="text" name="date_to[{{$code}}]" placeholder="Date to" class="form-control datetimepicker" value="{{Input::old('date_to.'.$code.'', null)}}">
                                @if($errors->has('date_to.'.$code.''))
                                    <div class="text-danger">{{ Lang::get($errors->first('date_to.'.$code.'')) }}</div>
                                @endif
                            </div>
                            <div class="form-group col-sm-4">
                                <button class="btn btn-success" type="submit">Get</button>
                            </div>
                        </div>
                    </form>
                </td>
            </tr>

            <tr>
                <td colspan="4">
                    <?php $code = '69';?>
                    <form role = "form" id="form_{{$code}}" class="form-inline" method="POST" action="{{URL::to('/pods/sensors/request/'.$code.'/pod/'.$pod->id)}}">

                        <div class="form-group col-sm-12">
                            <label class="font-bold text-primary"> Set individual alert temperatures for current pod.</label>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group col-sm-4">
                                <input type="text" name="alert_min[{{$code}}]" placeholder="Alert min" class="form-control" value="{{Input::old('alert_min.'.$code.'', null)}}">
                                @if($errors->has('alert_min.'.$code.''))
                                    <div class="text-danger">{{ Lang::get($errors->first('alert_min.'.$code.'')) }}</div>
                                @endif
                            </div>
                            <div class="form-group col-sm-4">
                                <input type="text" name="alert_max[{{$code}}]" placeholder="Alert max" class="form-control" value="{{Input::old('alert_max.'.$code.'.', null)}}">
                                @if($errors->has('alert_max.'.$code.''))
                                    <div class="text-danger">{{ Lang::get($errors->first('alert_max.'.$code.'')) }}</div>
                                @endif
                            </div>
                            <div class="form-group col-sm-4">
                                <button class="btn btn-success" type="submit">Set</button>
                            </div>
                        </div>
                    </form>
                </td>
            </tr>

            <tr>
                <td colspan="4">
                    <?php $code = '6A';?>
                    <form role = "form" id="form_{{$code}}" class="form-inline" method="POST" action="{{URL::to('/pods/sensors/request/'.$code.'/hub/'.$hub->id)}}">

                        <div class="form-group col-sm-12">
                            <label class="font-bold text-primary"> Set specific alert temperatures for all pods of current hub.</label>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group col-sm-4">
                                <input type="text" name="alert_min[{{$code}}]" placeholder="Alert min" class="form-control" value="{{Input::old('alert_min.'.$code.'', null)}}">
                                @if($errors->has('alert_min.'.$code.''))
                                    <div class="text-danger">{{ Lang::get($errors->first('alert_min.'.$code.'')) }}</div>
                                @endif
                            </div>
                            <div class="form-group col-sm-4">
                                <input type="text" name="alert_max[{{$code}}]" placeholder="Alert max" class="form-control" value="{{Input::old('alert_max.'.$code.'', null)}}">
                                @if($errors->has('alert_max.'.$code.''))
                                    <div class="text-danger">{{ Lang::get($errors->first('alert_max.'.$code.'')) }}</div>
                                @endif
                            </div>
                            <div class="form-group col-sm-4">
                                <button class="btn btn-success" type="submit">Set</button>
                            </div>
                        </div>
                    </form>
                </td>
            </tr>

            <tr>
                <td colspan="4">
                    <?php $code = '65';?>
                    <form role = "form" id="form_{{$code}}" class="form-inline" method="POST" action="{{URL::to('/pods/sensors/request/'.$code.'/pod/'.$pod->id)}}">

                        <div class="col-sm-12">
                            <div class="col-sm-8 no-padder">
                                <label class="font-bold text-primary"> Get the alert temperatures from current pod.</label>
                            </div>
                            <div class="form-group col-sm-4 col-sm-8">
                                <button class="btn btn-success" type="submit">Get</button>
                            </div>
                        </div>
                    </form>
                </td>
            </tr>

            <tr>
                <td colspan="4">
                    <?php $code = '66';?>
                    <form role = "form" id="form_{{$code}}" class="form-inline" method="POST" action="{{URL::to('/pods/sensors/request/'.$code.'/hub/'.$hub->id)}}">

                        <div class="col-sm-12">
                            <div class="col-sm-8 no-padder">
                                <label class="font-bold text-primary"> Get the alert temperatures from all pods of current Hub.</label>
                            </div>
                            <div class="form-group col-sm-4 col-sm-8">
                                <button class="btn btn-success" type="submit">Get</button>
                            </div>
                        </div>
                    </form>
                </td>
            </tr>

    <?php endif; ?>
<?php endif; ?>

            </tbody>
        </table>
    </div>
    <footer class="panel-footer">
        <div class="row">

        </div>
    </footer>
</section>
@endsection
@section('js')
    {{ Basset::show('package_datetimepicker.js') }}
    <script>
        $(document).ready(function(){

            $(".datetimepicker").datetimepicker({
                format: 'YYYY-MM-DD H:mm',
                icons: {
                    time: "fa fa-clock-o",
                    date: "fa fa-calendar",
                    up: "fa fa-arrow-up",
                    down: "fa fa-arrow-down"
                }
            });

        })
    </script>
@endsection
@section('css')
    {{Basset::show('package_datetimepicker.css') }}
    <style>
        .modal-dialog{width:400px;}
    </style>
@endsection