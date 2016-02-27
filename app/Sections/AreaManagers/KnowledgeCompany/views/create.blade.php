@extends('_manager.layouts.manager')
@section('title')
    @parent
    :: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')
    <div class="m-b-md">
        <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>{{$sectionName}}
            <span class="pull-right">
           <a class="btn btn-green inline" href="{{URL::to('/knowledge-company')}}"><i class="fa fa-search"></i> {{Lang::get('common/button.list')}} </a>
           <a class="btn btn-green inline" href="{{URL::to('/knowledge-company/create')}}"><i class="fa fa-plus"></i> {{Lang::get('common/button.create')}} </a>
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
                                <label class="col-sm-2 control-label">{{Lang::get('common/general.specific.content_one')}}</label>
                                <div class="col-sm-10 {{{ $errors -> has('content_one') ? 'has-error' : '' }}}">
                                    <textarea wyswig="basic" name="content_one"  class="form-control" placeholder="{{Lang::get('common/general.specific.content_one')}}">{{Input::old('content_one', null)}}</textarea>
                                    @if($errors->has('content_one'))
                                        <div class="text-danger">{{ Lang::get($errors->first('content_one')) }}</div>
                                    @endif
                                </div>
                            </div>
<!--
                            <div class="form-group">
                                <label class="col-sm-2 control-label">{{Lang::get('common/general.specific.content_two')}}</label>
                                <div class="col-sm-10 {{{ $errors -> has('content_two') ? 'has-error' : '' }}}">
                                    <textarea name="content_two"  class="form-control" placeholder="{{Lang::get('common/general.specific.content_two')}}">{{Input::old('content_one', null)}}</textarea>
                                    @if($errors->has('content_two'))
                                        <div class="text-danger">{{ Lang::get($errors->first('content_two')) }}</div>
                                    @endif
                                </div>
                            </div>
-->
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