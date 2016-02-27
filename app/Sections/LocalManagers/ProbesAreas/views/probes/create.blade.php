@extends('_panel.layouts.panel')
@section('title')
@parent
:: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')
<div class="m-b-md">
    <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>{{$sectionName}}
        <span class="pull-right">
           <a class="btn btn-green" href="{{URL::to('/probes/areas/'.$group->identifier)}}"><i class="material-icons">search</i> {{Lang::get('common/general.list')}} </a>
        </span>
    </h3>
</div>
@include('breadcrumbs',['data' => $breadcrumbs])

<div class="row">
    <div class="col-sm-12">
        <section class="panel panel-default padder">
            <form class="form-horizontal m-t" action="{{URL::to('/probes/areas/create/'.$group->identifier)}}" method="post">

                <div class="form-group">
                    <div class="col-sm-2">
                        <label class="control-label">{{Lang::get('common/general.name')}}</label>
                    </div>
                    <div class="col-sm-10 {{{ $errors->has('name') ? 'has-error' : '' }}}">
                        <input name="name" type="text" class="form-control" placeholder="{{Lang::get('common/general.name')}}" value="{{Input::old('name', null)}}">
                        @if($errors->has('name'))
                            <div class="text-danger">{{ Lang::get($errors->first('name')) }}</div>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-2">
                        <label class="control-label">{{Lang::get('common/general.description')}}</label>
                    </div>
                    <div class="col-sm-10 {{{ $errors->has('description') ? 'has-error' : '' }}}">
                        <textarea class="form-control" name="description" placeholder="{{Lang::get('common/general.description')}}">{{Input::old('description', null)}}</textarea>
                        @if($errors->has('description'))
                            <div class="text-danger">{{ Lang::get($errors->first('description')) }}</div>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-2">
                        <label class="control-label">{{Lang::get('common/general.rule_description')}}</label>
                    </div>
                    <div class="col-sm-10 {{{ $errors->has('rule_description') ? 'has-error' : '' }}}">
                        <textarea class="form-control" name="rule_description" placeholder="Please write description with placeholders.">{{Input::old('rule_description', $group->rule_description)}}</textarea>
                        <label class="text-xs" >{name}, {warning_min}, {valid_min}, {valid_max}, {warning_max}, {celsius}</label>
                        @if($errors->has('rule_description'))
                            <div class="text-danger">{{ Lang::get($errors->first('rule_description')) }}</div>
                        @endif
                    </div>
                </div>

                <div class="line line-dashed b-b line-lg pull-in"></div>
                <div class="form-group">
                    <div class="col-sm-3 danger-min">
                        <label class="">Warning min</label>
                        <input name="warning_min_slider" class="slider form-control col-sm-12" type="text" value="-10" data-slider-min="-50" data-slider-max="-10" data-slider-step="0.5" data-slider-value="-10" id="danger-min">
                        <div class="input-group m-t">
                            <input value="-10" name="warning_min" type="text" class="form-control text-right" readonly="">
                            <span class="input-group-addon hidden-sm">&#x2103</span>
                        </div>
                        @if($errors->has('warning_min'))
                            <div class="text-danger">{{ Lang::get($errors->first('warning_min')) }}</div>
                        @endif
                    </div>
                    <div class="col-sm-6 warning-range">
                        <label class="">Valid range</label>
                        <input name="warning_range_slider" class="slider form-control col-sm-12" type="text" value="-10,10" data-slider-min="-50" data-slider-max="100" data-slider-step="0.5" data-slider-value="[-10,10]" id="warning-range">
                        <div class="col-sm-6">
                            <div class="input-group m-t">
                                <input value="-10" name="valid_min" type="text" class="form-control text-right" readonly="">
                                <span class="input-group-addon hidden-sm">&#x2103</span>
                            </div>
                            @if($errors->has('valid_min'))
                                <div class="text-danger">{{ Lang::get($errors->first('valid_min')) }}</div>
                            @endif
                        </div>
                        <div class="col-sm-6">
                            <div class="input-group m-t">
                                <input value="10" name="valid_max" type="text" class="form-control text-right" readonly="">
                                <span class="input-group-addon hidden-sm">&#x2103</span>
                            </div>
                            @if($errors->has('valid_max'))
                                <div class="text-danger">{{ Lang::get($errors->first('valid_max')) }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-3 danger-max">
                        <label class="">Warning max</label>
                        <input name="warning_max_slider" class="slider form-control col-sm-12" type="text" value="10" data-slider-min="10" data-slider-max="100" data-slider-step="0.5" data-slider-value="10" id="danger-max" >
                        <div class="input-group m-t">
                            <input value="10" name="warning_max" type="text" class="form-control text-right" readonly="">
                            <span class="input-group-addon hidden-sm">&#x2103</span>
                        </div>
                        @if($errors->has('warning_max'))
                            <div class="text-danger">{{ Lang::get($errors->first('warning_max')) }}</div>
                        @endif
                    </div>
                </div>
                <div class="line line-dashed b-b line-lg pull-in"></div>

                <div class="form-group">
                    <div class="col-lg-offset-2 col-lg-10">
                        <div class="modal-footer">
                            <button class="btn btn-sm btn-green" type="submit">{{Lang::get('common/button.create')}}</button>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </div>
</div>
@endsection
@section('css')
    {{ HTML::style( 'assets/js/slider/slider.css') }}
    {{ HTML::style( 'assets/js/slider/temperatures-slider.css') }}
    {{ Basset::show('package_touchspin.css') }}
@endsection
@section('js')
    {{ HTML::script( 'assets/js/slider/bootstrap-slider.js') }}
    {{ HTML::script( 'assets/js/slider/temperatures-slider-init.js') }}
    {{ Basset::show('package_touchspin.js') }}
@endsection