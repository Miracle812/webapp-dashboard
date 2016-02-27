@extends('_panel.layouts.panel')
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
        <div class="col-lg-12">
            <section class="panel panel-default">
                <header class="panel-heading font-bold">{{$sectionName}} - {{$actionName}}</header>
                <div class="panel-body">
                    <form role="form" method="post">
                        <div class="form-group">
                            <label>{{\Lang::get('/common/general.pod_device')}}</label>
                            <select name="pod_id" class="form-control">
                                @foreach($pods as $pod)
                                <option value="{{$pod->id}}">{{$pod->name}} <span class="text-muted small">[ @if($area = $pod->area()) Assigned to {{implode(array_merge($area->getParentsNames(),[$area->name]),' / ')}}. @else Not Assigned @endif ]</span></option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label class="col-sm-4 control-label">{{Lang::get('common/general.temperature')}}</label>
                                        <div class="col-sm-8  {{{ $errors->has('temperature') ? 'has-error' : '' }}}">
                                            <div class="">
                                                <input name="temperature" class="form-control col-sm-9" type="text" value="{{Input::old('temperature', 0)}}"  data-ride="spinner" data-min='-50' data-max="100" data-step="0.1" data-decimals="1" data-postfix="&#x2103">
                                                @if($errors->has('temperature'))
                                                    <div class="text-danger">{{ Lang::get($errors->first('temperature')) }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 hide">
                                        <label class="col-sm-4 control-label">{{Lang::get('common/general.voltage')}}</label>
                                        <div class="col-sm-8  {{{ $errors->has('voltage') ? 'has-error' : '' }}}">
                                            <div class="">
                                                <input name="voltage" class="form-control col-sm-9" type="text" value="{{Input::old('voltage', 3)}}"  data-ride="spinner" data-min='-50' data-max="100" data-step="0.1" data-decimals="1" data-postfix="V">
                                                @if($errors->has('voltage'))
                                                    <div class="text-danger">{{ Lang::get($errors->first('voltage')) }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-sm btn-success" type="submit">{{\Lang::get('/common/button.create')}}</button>
                    </form>
                </div>
            </section>
        </div>
    </div>
@endsection
@section('css')
    {{ Basset::show('package_touchspin.css') }}
@endsection
@section('js')
    {{ Basset::show('package_touchspin.js') }}@endsection

