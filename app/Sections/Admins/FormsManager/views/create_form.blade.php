@extends('_admin.layouts.admin')
@section('title')
    @parent
    :: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')
    <div class="m-b-md">
        <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>{{$sectionName}}
            <span class="pull-right">
           <a class="btn btn-green" href="{{URL::to("/form-builder")}}"><i class="fa fa-search"></i> {{Lang::get('common/general.list')}} </a>
        </span>
        </h3>
    </div>
    @include('breadcrumbs',['data' => $breadcrumbs])
    <div class="row">
        <div class="col-sm-12">
            <section class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <form role="form" action="{{URL::to('/forms-manager/create')}}" method="post">
                            <div class="col-sm-12">
                                <div class="form-group {{{ $errors->has('name') ? 'has-error' : '' }}}">
                                    <label>{{\Lang::get('/common/general.name')}}</label>
                                    <input name="name" type="text" value="{{Input::old('name', null)}}" placeholder="{{\Lang::get('/common/general.name')}}" class="form-control">
                                    @if($errors->has('name'))
                                        <div class="text-danger">{{ Lang::get($errors->first('name')) }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group {{{ $errors->has('assigned') ? 'has-error' : '' }}}">
                                    <label>{{\Lang::get('/common/general.assigned')}}</label>
                                    <select name="assigned" class="form-control">
                                        @foreach($assigned as $key => $assing)
                                            <option @if(Input::old('assigned', null)==$key) selected @endif value="{{$key}}">{{\Lang::get('/common/general.forms_manager.'.$assing)}}</option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('assigned'))
                                        <div class="text-danger">{{ Lang::get($errors->first('assigned')) }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div id="groups-selector" data-url="{{URL::to('/forms-manager/load-groups')}}/" class="hide form-group {{{ $errors->has('group') ? 'has-error' : '' }}}">
                                    <label>{{\Lang::get('/common/general.group')}}</label>
                                    <div id="groups-loader"></div>
                                    @if($errors->has('group'))
                                        <div class="text-danger">{{ Lang::get($errors->first('group')) }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group {{{ $errors->has('new_group') ? 'has-error' : '' }}}">
                                    <label>{{\Lang::get('/common/general.new_group')}}</label>
                                    <input name="new_group" type="text" value="{{Input::old('new_group', null)}}" placeholder="{{\Lang::get('/common/general.new_group')}}" class="form-control">
                                    @if($errors->has('new_group'))
                                        <div class="text-danger">{{ Lang::get($errors->first('new_group')) }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group {{{ $errors->has('active') ? 'has-error' : '' }}}">

                                    <div class="checkbox i-checks">
                                        <label>
                                            <input name="active" type="checkbox" @if(Input::old('active', null))checked=""@endif><i></i> Active
                                        </label>
                                    </div>

                                    @if($errors->has('active'))
                                        <div class="text-danger">{{ Lang::get($errors->first('active')) }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group {{{ $errors->has('description') ? 'has-error' : '' }}}">
                                    <label>{{\Lang::get('/common/general.description')}}</label>
                                    <textarea name="description" wyswig='basic' type="text" placeholder="{{\Lang::get('/common/general.description')}}" class="form-control">{{Input::old('description', null)}}</textarea>
                                </div>
                                @if($errors->has('description'))
                                    <div class="text-danger">{{ Lang::get($errors->first('description')) }}</div>
                                @endif
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <button class="btn btn-sm btn-success" type="submit">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
@section('js')
<script>
$(document).ready(function(){
    $("select[name=assigned] option:first").attr('selected','selected');
    var assigned = $('select[name=assigned]').val();
    var groupsSelector = $('#groups-selector');
    function getGroups(){
        $.get(groupsSelector.data('url')+assigned,function(data){
            if(data.length) {
                $('#groups-loader').html(data);
                $(groupsSelector).removeClass('hide');
            }
            else
                $(groupsSelector).addClass('hide');
        })
    }
    $('select[name=assigned]').on('change', function(){
        assigned = this.value;
        getGroups();
    });
    getGroups();
});
</script>
@endsection

