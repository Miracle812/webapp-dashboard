@extends('_manager.layouts.manager')
@section('title')
@parent
:: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')
<div class="m-b-md">
    <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>{{$sectionName}}
        <span class="pull-right">
           <a class="btn btn-green inline" href="{{URL::to('/haccp-sites')}}"><i class="fa fa-search"></i> {{Lang::get('common/button.list')}} </a>
        </span>
    </h3>
</div>
@include('breadcrumbs',['data' => $breadcrumbs])

<div class="row">
    <div class="col-sm-12">
        <section class="panel panel-default">
            <header class="panel-heading font-bold">
                {{$sectionName}} - {{$actionName}}
            </header>
            <div class="panel-body">
                <form class="form-horizontal m-t" method="post">

                    <div class="form-group">
                        <label class="col-sm-2 control-label">{{\Lang::get('/common/general.title')}}</label>
                        <div class="col-sm-10 {{{ $errors -> has('title') ? 'has-error' : '' }}}">
                            <input name="title" type="text" class="form-control" placeholder="{{Lang::get('common/general.title')}}" value="{{Input::old('title', null)}}">
                            @if($errors->has('title'))
                                <div class="text-danger">{{ Lang::get($errors->first('title')) }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            <button data-toggle="class:show" class="btn btn-sm btn-default active" href="#general">
                              <i class="fa fa-plus text-active"></i>
                              <span class="text-active">Click for More</span>
                              <i class="fa fa-minus text"></i>
                              <span class="text">Less</span>
                            </button>
                        </div>
                    </div>
                    <div id="general" class="collapse">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">{{\Lang::get('/common/general.content')}}</label>
                            <div class="col-sm-10 {{{ $errors -> has('content') ? 'has-error' : '' }}}">
                                <textarea wyswig="basic-upload" name="content"  class="form-control" placeholder="{{Lang::get('common/general.content')}}">{{Input::old('content', null)}}</textarea>
                                @if($errors->has('content'))
                                    <div class="text-danger">{{ Lang::get($errors->first('content')) }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">{{\Lang::get('/common/general.hazards')}}</label>
                            <div class="col-sm-10 {{{ $errors -> has('content') ? 'has-error' : '' }}}">
                                <textarea wyswig="basic-upload" name="hazards"  class="form-control" placeholder="{{Lang::get('common/general.hazards')}}">{{Input::old('hazards', null)}}</textarea>
                                @if($errors->has('hazards'))
                                    <div class="text-danger">{{ Lang::get($errors->first('hazards')) }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">{{\Lang::get('/common/general.control')}}</label>
                            <div class="col-sm-10 {{{ $errors -> has('control') ? 'has-error' : '' }}}">
                                <textarea wyswig="basic-upload" name="control"  class="form-control" placeholder="{{Lang::get('common/general.control')}}">{{Input::old('control', null)}}</textarea>
                                @if($errors->has('control'))
                                    <div class="text-danger">{{ Lang::get($errors->first('control')) }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">{{\Lang::get('/common/general.monitoring')}}</label>
                            <div class="col-sm-10 {{{ $errors -> has('monitoring') ? 'has-error' : '' }}}">
                                <textarea wyswig="basic-upload" name="monitoring"  class="form-control" placeholder="{{Lang::get('common/general.monitoring')}}">{{Input::old('monitoring', null)}}</textarea>
                                @if($errors->has('monitoring'))
                                    <div class="text-danger">{{ Lang::get($errors->first('monitoring')) }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">{{\Lang::get('/common/general.corrective_action')}}</label>
                            <div class="col-sm-10 {{{ $errors -> has('corrective_action') ? 'has-error' : '' }}}">
                                <textarea wyswig="basic-upload" name="corrective_action"  class="form-control" placeholder="{{Lang::get('common/general.corrective_action')}}">{{Input::old('corrective_action', null)}}</textarea>
                                @if($errors->has('corrective_action'))
                                    <div class="text-danger">{{ Lang::get($errors->first('corrective_action')) }}</div>
                                @endif
                            </div>
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