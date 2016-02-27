@extends('newlayout.base')
@section('title')
    @parent
    :: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')

    <div id="page_content">
        <div id="page_content_inner">

            <h2 class="heading_b uk-margin-bottom">{{$sectionName}}</h2>

            <?php /*@include('breadcrumbs',['data' => $breadcrumbs]) */?>

            <div class="md-card">

                <div class="md-card-toolbar">
                    <h3 class="md-card-toolbar-heading-text large">
                        {{$sectionName}} - {{$actionName}}
                    </h3>
                </div>

                <div class="md-card-content">

                    <form id="frm_probe_devices_edit"  method="post">
                        <input type="hidden" name="_token" value="<?= csrf_token() ?>"/>

                        <div class="md-card-content">
                            <div class="uk-grid">
                                <div class="uk-width-medium-1-6">
                                    <label>{{\Lang::get('/common/general.sn')}}</label>
                                </div>
                                <div class="uk-width-medium-5-6">
                                    <p class="form-control-static h4 text-default font-bold">{{$probe->device_id}}</p>
                                </div>
                            </div>

                            <div class="uk-grid">
                                <div class="uk-width-medium-1-6">
                                    <label>{{\Lang::get('/common/general.status')}}</label>
                                </div>
                                <div class="uk-width-medium-5-6">
                                    <p class="form-control-static text-default font-bold"><i title="" class="m-r fa @if($probe->status=='create') fa-gears uk-text-danger @else  fa-check md-color-green-600 @endif" ></i>@if($probe->status=='create') <span class="uk-text-danger">{{\Lang::get('/common/general.connection_process')}}</span> @else {{\Lang::get('/common/general.ready_to_work')}} @endif</p>
                                </div>
                            </div>
                            <div class="uk-grid">
                                <div class="uk-width-medium-1-6">
                                    <label>{{\Lang::get('/common/general.created_at')}}</label>
                                </div>
                                <div class="uk-width-medium-5-6">
                                    <p class="form-control-static text-default">{{$probe->created_at()}}</p>
                                    <p class="text-sm md-color-blue-600 font-bold"> {{$probe->date()}}</p>
                                </div>
                            </div>
                        </div>

                        <hr class="md-hr" />

                        <div class="md-card-content">

                            <div class="uk-grid">
                                <div class="uk-width-1-1">
                                    <label>{{\Lang::get('/common/general.name')}}</label>
                                    <input type="text"
                                           class="md-input"
                                           maxlength="50"
                                           name="name" value="{{$probe->name}}" />
                                    @if($errors->has('name'))
                                        <div class="uk-text-danger">{{ Lang::get($errors->first('name')) }}</div>
                                    @endif
                                </div>

                            </div>

                            <div class="uk-grid">
                                <div class="uk-width-1-1">
                                    <label>{{\Lang::get('/common/general.description')}}</label>
                                    <textarea class="md-input" name="description">{{$probe->description}}</textarea>
                                    @if($errors->has('description'))
                                        <div class="uk-text-danger">{{ Lang::get($errors->first('description')) }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="uk-grid">
                                <div class="uk-width-medium-1-2">
                                    <label>{{\Lang::get('/common/general.pin')}}</label>

                                    <input type="password" id="txtPassword"
                                           class="md-input"
                                           maxlength="10"
                                            name="pin" value="{{$probe->pin}}" />
                                    @if($errors->has('pin'))
                                        <div class="uk-text-danger">{{ Lang::get($errors->first('pin')) }}</div>
                                    @endif
                                </div>

                                <div class="uk-width-medium-1-2">
                                    <div class="m-t">
                                        <input type="checkbox" id="chkShowPassword">
                                        <label for="chkShowPassword" class="inline-label">{{\Lang::get('/common/general.show_pin')}}</label>
                                    </div>
                                </div>
                            </div>
                            @if($probe->status=='linked')
                            <div class="uk-grid">
                                <div class="uk-width-medium-1-6">
                                    <label>{{\Lang::get('/common/general.enabled')}}</label>
                                </div>
                                <div class="uk-width-medium-5-6">
                                    <input type="checkbox" data-switchery data-switchery-color="#43a047" name="active" @if($probe->active)checked @endif />
                                </div>
                            </div>
                            @endif
                        
                        </div>

                        <hr class="md-hr" />

                        <div class="uk-grid">
                            <div class="uk-width-1-1 uk-text-right">
                                <button class="md-btn md-btn-success md-btn-wave-light waves-effect waves-button waves-light" type="submit">{{\Lang::get('/common/button.update')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
<script type="text/javascript">
$(document).ready(function(){
    $( '#chkShowPassword').on('ifChecked', function(event) {
        $("#txtPassword").attr('type','text')
    } ).on( 'ifUnchecked', function(event) {
        $("#txtPassword").attr('type','password')
    }).iCheck({
        checkboxClass: 'icheckbox_md'
    });
});
</script>
@endsection