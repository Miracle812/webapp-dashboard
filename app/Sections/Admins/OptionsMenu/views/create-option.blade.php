@extends('_admin.layouts.admin')
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
                <header class="panel-heading font-bold">
                    {{$sectionName}} - {{$actionName}} option for {{$optionsMenu->name}}
                </header>
                <div class="panel-body">
                    <form class="form-horizontal m-t" method="post">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">{{\Lang::get('/common/general.identifier')}}</label>
                            <div class="col-sm-10 {{{ $errors -> has('identifier') ? 'has-error' : '' }}}">
                                <input name="identifier" type="text" class="form-control" placeholder="{{Lang::get('common/general.identifier')}}" value="{{Input::old('identifier', null)}}">
                                @if($errors->has('identifier'))
                                    <div class="text-danger">{{ Lang::get($errors->first('identifier')) }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">{{\Lang::get('/common/general.name')}}</label>
                            <div class="col-sm-10 {{{ $errors -> has('name') ? 'has-error' : '' }}}">
                                <input name="name" type="text" class="form-control" placeholder="{{Lang::get('common/general.name')}}" value="{{Input::old('title', null)}}">
                                @if($errors->has('name'))
                                    <div class="text-danger">{{ Lang::get($errors->first('name')) }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">{{\Lang::get('/common/general.type')}}</label>
                            <div class="col-sm-10 {{{ $errors -> has('name') ? 'has-error' : '' }}}">
                                <select name="type" class="form-control">
                                    <option value="">Select Type</option>
                                    <option value="feature">Feature</option>
                                    <option value="checkbox">Checkbox</option>
                                    <option value="input">Input</option>
                                </select>
                                @if($errors->has('type'))
                                    <div class="text-danger">{{ Lang::get($errors->first('type')) }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-10">
                                <div class="modal-footer">
                                    <button class="btn btn-green" type="submit">{{Lang::get('common/button.create')}}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>
@endsection