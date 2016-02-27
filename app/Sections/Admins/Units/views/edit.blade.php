@extends('_admin.layouts.admin')
@section('title')
@parent
:: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')
<div class="m-b-md">
    <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>Sites
        <span class="pull-right">
           <a class="btn btn-green inline" href="{{URL::to("/units/create")}}"><i class="fa fa-plus"></i> {{Lang::get('common/button.create')}} </a>
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
                <form class="form-horizontal" method="post">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <div class="col-sm-12 {{{ $errors->has('name') ? 'has-error' : '' }}}">
                                    <div class="h4 text-primary font-bold">{{\Lang::get('/common/general.address')}}:</div>
                                    <label class="control-label">{{\Lang::get('/common/general.name')}}</label>
                                    <input type="text"
                                           class="form-control"
                                           placeholder="{{\Lang::get('/common/general.name')}}"
                                           maxlength="50"
                                           name="name" value="{{Input::old('name', $unit->name)}}"/>
                                    @if($errors->has('name'))
                                        <div class="text-danger">{{ Lang::get($errors->first('name')) }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-6 {{{ $errors->has('post_code') ? 'has-error' : '' }}}">
                                    <label class="control-label">{{\Lang::get('/common/general.post_code')}}</label>
                                    <input type="text"
                                           class="form-control gmaploc"
                                           placeholder="{{\Lang::get('/common/general.post_code')}}"
                                           maxlength="50"
                                           name="post_code" value="{{Input::old('post_code', $unit->post_code)}}" />
                                    @if($errors->has('post_code'))
                                        <div class="text-danger">{{ Lang::get($errors->first('post_code')) }}</div>
                                    @endif
                                </div>
                                <div class="col-sm-6 {{{ $errors->has('city') ? 'has-error' : '' }}}">
                                    <label class="control-label">{{\Lang::get('/common/general.city')}}</label>
                                    <input type="text"
                                           class="form-control gmaploc"
                                           placeholder="{{\Lang::get('/common/general.city')}}"
                                           maxlength="50"
                                           name="city" value="{{Input::old('city', $unit->city)}}" />
                                    @if($errors->has('city'))
                                        <div class="text-danger">{{ Lang::get($errors->first('city')) }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12 {{{ $errors->has('street_number') ? 'has-error' : '' }}}">
                                    <label class="control-label">{{\Lang::get('/common/general.street_number')}}</label>
                                    <input type="text"
                                           class="form-control gmaploc"
                                           placeholder="{{\Lang::get('/common/general.street_number')}}"
                                           maxlength="50"
                                           name="street_number" value="{{Input::old('street_number', $unit->street_number)}}" />
                                    @if($errors->has('street_number'))
                                        <div class="text-danger">{{ Lang::get($errors->first('street_number')) }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-4 {{{ $errors->has('phone') ? 'has-error' : '' }}}">
                                    <label class="control-label">{{\Lang::get('/common/general.phone')}}</label>
                                    <input type ="text"
                                           class="form-control"
                                           placeholder="{{\Lang::get('/common/general.phone')}}"
                                           maxlength="50"
                                           name="phone"
                                           value="{{Input::old('phone', $unit->phone)}}" />
                                    @if($errors->has('phone'))
                                        <div class="text-danger">{{ Lang::get($errors->first('phone')) }}</div>
                                    @endif
                                </div>
                                <div class="col-sm-4 {{{ $errors->has('mobile_phone') ? 'has-error' : '' }}}">
                                    <label class="control-label">{{\Lang::get('/common/general.mobile_phone')}}</label>
                                    <input type="text"
                                           class="form-control"
                                           placeholder="{{\Lang::get('/common/general.mobile_phone')}}"
                                           maxlength="50"
                                           name="mobile_phone"
                                           value="{{Input::old('mobile_phone', $unit->mobile_phone)}}" />
                                    @if($errors->has('mobile_phone'))
                                        <div class="text-danger">{{ Lang::get($errors->first('mobile_phone')) }}</div>
                                    @endif
                                </div>
                                <div class="col-sm-4 {{{ $errors->has('email') ? 'has-error' : '' }}}">
                                    <label class="control-label">{{\Lang::get('/common/general.email')}}</label>
                                    <input type="text"
                                           class="form-control"
                                           placeholder="{{\Lang::get('/common/general.email')}}"
                                           maxlength="50"
                                           name="email"
                                           value="{{Input::old('email', $unit->email)}}" />
                                    @if($errors->has('email'))
                                        <div class="text-danger">{{ Lang::get($errors->first('email')) }}</div>
                                    @endif
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
                    <h3>{{\Lang::get('/common/general.options')}}</h3>
                    <div class="form-group b-t">
                        <div class="col-sm-4 m-t">
                            <a class="btn btn-sm btn-success" href="{{URL::to('/units/manage-unit-modules/'.$unit->id)}}">Manage modules List</a>
                        </div>
                    </div>
                    <?php $options = $parentOptions->childrens ?>
                    @if($options->count())
                        <div class="form-group b-t">
                            <div class="col-sm-6 m-t">
                                @foreach($options as $option)
                                    @if(in_array($option->type,['checkbox']))
                                        <?php $inputName = 'options['.$option->id.']';?>
                                        <div class="col-sm-12">
                                            <input type="checkbox" @if(Input::old($inputName, $unit->hasOption($option->identifier))) checked @endif name="{{$inputName}}" value="1" />
                                            {{$option->name}}
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                    <div class="form-group">
                        <div class="modal-footer">
                            <button class="btn btn-lg btn-success">{{\Lang::get('/common/button.update')}}</button>
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
    {{ Basset::show('package_maskedinput.js') }}
    <script>
        $(document).ready(function() {
            $("input[name=mobile_phone]").mask("(+99) 9999 99999?9999");
        });
    </script>
@endsection