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
                <?php  $yes = '#1aae88';     $no = '#e33244';   ?>
                <div class="form-group m-b">
                    <label class="col-sm-12">{{\Lang::get('/common/general.buttons_colors')}}</label>
                    <div class="col-sm-6">
                        <div class="input-group">
                            <span class="input-group-btn">
                                <button id="button-yes" class="btn dropdown-toggle text-white" style="background-color: {{$yes}}"  data-toggle="dropdown" >YES</button>
                                <ul class="dropdown-menu">
                                    <li><div id="colorpalette_yes"></div></li>
                                </ul>
                            </span>
                            <input name="color_yes" id="selected-color-yes" class="form-control" value="{{$yes}}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="input-group ">
                            <div class="input-group-btn">
                                <button id="button-no" class="btn dropdown-toggle text-white" style="background-color: {{$no}}" data-toggle="dropdown">No</button>
                                <ul class="dropdown-menu">
                                    <li><div id="colorpalette_no"></div></li>
                                </ul>
                            </div>
                            <input name="color_no" id="selected-color-no" class="form-control" value="{{$no}}" readonly>
                        </div>
                    </div>
                </div>
                <div class="form-group panel panel-default">
                    <div class="panel-body">
                        <label>{{\Lang::get('/common/general.options')}}</label>
                        <div id="select_options" class="row padder m-b">

                        </div>
                        <button id="add-option" class="btn btn-primary"><i class="fa fa-plus m-r"></i>Add option</button>
                    </div>
                </div>
                <button class="btn btn-success" type="submit">Save</button>
            </form>
        </div>
    </section>
    <div class="clearfix"></div>
@endsection
@section('css')
    {{ Basset::show('package_colorpalete.css') }}
    <style>
        .w600{max-width:600px}
        .input-group .text-danger{display: table-caption;}
    </style>
@endsection
@section('js')
    {{ Basset::show('package_colorpalete.js') }}
    @include('Sections\LocalManagers\FormsManager::partials.options_list_js');
    <script>
        $(document).ready(function(){
            $('#colorpalette_yes').colorPalette()
                    .on('selectColor', function(e) {
                        $('#selected-color-yes').val(e.color);
                        $('#button-yes').css('background-color', e.color);

                    });
            $('#colorpalette_no').colorPalette()
                    .on('selectColor', function(e) {
                        $('#selected-color-no').val(e.color);
                        $('#button-no').css('background-color', e.color);
                    });

        });
    </script>
@endsection