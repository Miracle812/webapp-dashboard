@extends('_default.modals.modal')
@section('title')
    @parent
    {{$sectionName}} - {{$actionName}}
@endsection
@section('class_modal')
    w600
@endsection
@section('content')
    <section class="panel panel-default">
        <header class="panel-heading font-bold">{{$form->name}} :: {{\Lang::get('/common/general.'.$type)}}</header>
        <div class="panel-body">
            <form role="form" data-action="{{URL::to('/forms-manager/form/'.$form->id.'/add/'.$type)}}">
                <div class="form-group">
                    <label>{{\Lang::get('/common/general.label')}}</label>
                    <input name="label" type="text" placeholder="{{\Lang::get('/common/general.label')}}" class="form-control">
                </div>
                <div class="form-group">
                    <label>{{\Lang::get('/common/general.description')}}</label>
                    <textarea wyswig='basic' name="description" placeholder="{{\Lang::get('/common/general.description')}}" class="form-control"></textarea>
                </div>
                <div class="form-group panel panel-default">
                    <div class="panel-body">
                        <label>{{\Lang::get('/common/general.options')}}</label>
                        <div id="select_options" class="row padder m-b">

                        </div>
                        <button id="add-option" class="btn btn-primary"><i class="fa fa-plus m-r"></i>Add option</button>
                    </div>
                    <div class="padder radio i-checks">
                        Arrangement:
                        <label>
                            <input name="arrangement" type="radio" value="horizontal"><i></i> Horizontal
                        </label>
                        <label>
                            <input name="arrangement" type="radio" value="vertical"><i></i> Vertical
                        </label>
                    </div>
                </div>
                <div class="checkbox i-checks">
                    <label>
                        <input name="required" type="checkbox"><i></i> Required
                    </label>
                </div>
                <button class="btn btn-success" type="submit">Save</button>
            </form>
        </div>
    </section>
    <div class="clearfix"></div>
@endsection
@section('css')
    <style>
        .w600{max-width:600px}
    </style>
@endsection
@section('js')
    @include('Sections\Admins\FormsManager::partials.options_list_js');
@endsection