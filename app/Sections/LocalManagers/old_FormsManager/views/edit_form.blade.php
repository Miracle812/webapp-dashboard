@extends('_panel.layouts.panel')
@section('title')
@parent
:: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')
<div class="m-b-md">
    <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>{{$sectionName}}
        <span class="pull-right">
           <a class="btn btn-green" href="{{URL::to("/forms-manager/create")}}"><i class="material-icons">add</i> {{Lang::get('common/button.create')}} </a>
           <a class="btn btn-green" href="{{URL::to("/forms-manager")}}"><i class="material-icons">search</i> {{Lang::get('common/general.list')}} </a>
        </span>
    </h3>
</div>
@include('breadcrumbs',['data' => $breadcrumbs])
<div class="row">
    <div class="col-sm-12">
        <section class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <button data-toggle="class:show" class="btn btn-sm btn-default active" href="#general">
                                <i class="fa fa-plus text-active"></i>
                                <span class="text-active">Edit head data of form.</span>
                                <i class="fa fa-minus text"></i>
                                <span class="text">Hide form editor.</span>
                            </button>
                            <a data-toggle="ajaxModal" href="{{URL::to('/forms-manager/form/'.$form->id.'/display')}}" class="btn btn-rounded btn-sm btn-icon btn-success m-r inline tooltip-link pull-right" title="Display"><i class="material-icons">search</i></a>
                        </div>
                    </div>
                    <div id="general" class="collapse">
                        <form role="form" data-action="{{URL::to('/forms-manager/edit/form/'.$form->id)}}">
                            <div class="col-sm-12">
                                <div class="form-group {{{ $errors->has('name') ? 'has-error' : '' }}}">
                                    <label>{{\Lang::get('/common/general.name')}}</label>
                                    <input name="name" type="text" value="{{Input::old('name', $form->name)}}" placeholder="{{\Lang::get('/common/general.name')}}" class="form-control">
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
                                            <option @if(Input::old('assigned', $form->assigned_id)==$key) selected @endif value="{{$key}}">{{\Lang::get('/common/general.forms_manager.'.$assing)}}</option>
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
                                            <input name="active" type="checkbox" @if(Input::old('active', $form->active))checked=""@endif><i></i> Active
                                        </label>
                                    </div>
                                    @if($errors->has('active'))
                                        <div class="text-danger">{{ Lang::get($errors->first('active')) }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>{{\Lang::get('/common/general.description')}}</label>
                                    <textarea  wyswig='basic' name="description" type="text" placeholder="{{\Lang::get('/common/general.description')}}" class="form-control">{{$form->description}}</textarea>
                                </div>
                                <button class="btn btn-sm btn-success" type="submit">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-lg-3 col-md-4">
                        <h4> Available items:</h4>
                        @foreach($types as $key => $val)
                            <?php $based = in_array($key, ['submit_button','assign_staff','compliant']);?>
                            <a class="btn {{$based?'btn-danger':'btn-default'}} btn-block text-xs create-item" data-toggle="ajaxModal" href="{{URL::to("/forms-manager/form/$form->id/add/$key")}}">
                                <i class="{{$based?'':'text-success'}} fa fa-plus pull-right hidden-sm"></i>
                                <i class="{{$based?'':'text-primary'}} fa pull-left {{$val}}"></i>
                                {{\Lang::get('/common/general.'.$key)}}
                            </a>
                        @endforeach
                        <div class="text-xs text-default m-t"><i class="fa fa-info m-r"></i> Click on item button and move to form items list.</div>
                    </div>
                    <div class="col-xs-12 col-lg-9 col-md-8">
                        <h4> Form items:</h4>
                        <div class="dd nestable"  data-max_depth="2" >
                            <ol class="dd-list" id="items-list" data-url="{{URL::to('/forms-manager/sort-update')}}">
                                @include('Sections\LocalManagers\FormsManager::partials.form-items.item', ['pageItems'=>$tree, 'first'=>true, 'types'=>$types,'refresh'=>0])
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
@section('css')
    {{ Basset::show('package_nestable.css') }}
    {{ Basset::show('package_editable.css') }}
    <style>
        .panel .list-group-item {border-color: #d3d5d7;}
        .dd-handle{padding:20px 0;}
        .item-tab >.dd3-content {background: #eaf7ff; color:#fff;}

        .item-area .editable{color:#fff;}
        .item-area .editable:hover{color:#000;}
    </style>
@endsection
@section('js')
{{ Basset::show('package_nestable.js') }}
{{ Basset::show('package_sortable.js') }}
{{ Basset::show('package_editable.js') }}
<script>

var reinitializeItemsList = function() {
    $.fn.editable.defaults.placement = 'top';
    $('a.editable').editable();
    $('[data-toggle="tooltip"]').tooltip();
};
var initializeItemsList = function() {
    if($('.nestable').length)
    {
        maxDepth = $('.nestable').data('max_depth');
        $('.nestable').nestable({
            maxDepth : maxDepth ? maxDepth : 2
        }).on('change', function(e){
            e.preventDefault();
            if(e.handled == 1){
                e.handled = 1;
                return false;
            }
            updateOutput();
        });

    };
};
$(document).ready(function()
{
    initializeItemsList();
    reinitializeItemsList();
    $("form").on('submit', function(e){
        e.preventDefault();
        if(e.handled == 1){
            e.handled = 1;
            return false;
        }
        var form = $(this);
        data = form.serialize();
        url = form.data('action');
        $.ajax({
            context: { element: form },
            url: url,
            type: "post",
            dataType: "json",
            data:data
        });
    });



    var assigned = $('select[name=assigned]').val();
    var groupsSelector = $('#groups-selector');
    function getGroups(){
        $.get(groupsSelector.data('url')+assigned+'/{{$form->id}}',function(data){
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
    $(document).on('click', 'a[data-toggle="ajaxAction"]',  function(e){
        e.preventDefault();
        if(e.handled == 1){
            e.handled = 1;
            return false;
        }
        if($(this).data('action') == 'copy'){
            url = $(this).attr('href');
            $.get(url, function(data){
                if(data.type == 'success') {
                    $('ol#items-list').load('/forms-manager/form/{{$form->id}}/refresh-items', function () {
                        reinitializeItemsList();
                    });
                }
            });
        }

    });
    //<a data-toggle="ajaxAction" data-action="copy"


});
</script>
@endsection