@extends('newlayout.base')
@section('title')
    @parent
    :: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')


    <div id="page_content">
        <div id="page_content_inner">
            <h2 class="heading_b uk-margin-bottom">{{$sectionName}}
                <span class="panel-action">
                    <a class="md-btn md-bg-green-600 md-color-white md-btn-wave-light waves-effect waves-button waves-light" href="{{URL::to('/navinotes')}}"><i class="material-icons">search</i> {{Lang::get('common/general.list')}} </a>
                </span>
            </h2>

            <?php /*@include('breadcrumbs',['data' => $breadcrumbs]) */?>

            <div class="md-card">

                <div class="md-card-toolbar">
                    <h3 class="md-card-toolbar-heading-text large">
                        {{$sectionName}} - {{$actionName}}
                    </h3>
                </div>

                <div class="md-card-content">

                    <form method="post" action="{{URL::to("/navinotes/create")}}">
                        <input type="hidden" name="_token" value="<?= csrf_token() ?>"/>

                        <div class="uk-grid">

                            <div class="uk-width-medium-1-3 {{{ $errors->has('priority') ? 'has-error' : '' }}}">
                                <p>{{Lang::get('common/general.priority')}}</p>
                                <?php $priority = [
                                    'high'=>\Lang::get('/common/general.high'),
                                    'medium'=>\Lang::get('/common/general.medium'),
                                    'low'=>\Lang::get('/common/general.low'),
                                ];?>
                                {{Form::select('priority', $priority, Input::old('priority', null), array('class'=>'m-b', 'data-md-selectize'=>''))}}
                                @if($errors->has('priority'))
                                <div class="uk-text-danger">{{ Lang::get($errors->first('priority')) }}</div>
                                @endif
                            </div>

                            <div class="uk-width-medium-1-3 {{{ $errors->has('start') ? 'has-error' : '' }}}">
                                <p>{{\Lang::get('/common/general.date_start')}}:</p>
                                <input name="start" type="text" class="datetimepicker" value="{{Input::old('start',null)}}">
                                @if($errors->has('start'))
                                <div class="uk-text-danger">{{ Lang::get($errors->first('start')) }}</div>
                                @endif
                            </div>

                            <div class="uk-width-medium-1-3 {{{ $errors->has('end') ? 'has-error' : '' }}}">
                                <p>{{\Lang::get('/common/general.date_end')}}:</p>
                                <input name="end" type="text" class="datetimepicker" value="{{Input::old('end',null)}}">
                                @if($errors->has('end'))
                                <div class="uk-text-danger">{{ Lang::get($errors->first('end')) }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="uk-grid">
                            <div class="uk-width-1-1 {{{ $errors->has('name') ? 'has-error' : '' }}}">
                                <label>{{\Lang::get('/common/general.name')}}:</label>
                                <input name="name" type="text" class="md-input" value="{{Input::old('name', null)}}">
                                @if($errors->has('name'))
                                <div class="uk-text-danger">{{ Lang::get($errors->first('name')) }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="uk-grid">
                            <div class="uk-width-1-1 {{{ $errors->has('name') ? 'has-error' : '' }}}">
                                <label>{{\Lang::get('/common/general.description')}}:</label>
                                <textarea name="description" class="md-input">{{Input::old('description', null)}}</textarea>
                                @if($errors->has('description'))
                                <div class="uk-text-danger">{{ Lang::get($errors->first('description')) }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="uk-grid">
                            <div class="uk-width-1-1">
                                <label>{{\Lang::get('/common/general.files')}}</label>
                                <div id="media" class="uk-grid">
                                    <?php
                                    $targetType = 'navinotes';
                                    $options = Config::get('files_uploader.'.$targetType);
                                    $target = [
                                            'target_type' => $targetType,
                                            'target_id' => 'create.'.\Auth::user()->id
                                    ];
                                    ?>
                                    {{\FormExt::common_files_uploader($options,$target)}}
                                </div>

                            </div>
                        </div>

                        <hr class="md-hr" />

                        <div class="uk-grid">
                            <div class="uk-width-1-1 uk-text-right m-t">
                                <button class="md-btn md-btn-success md-btn-wave-light waves-effect waves-button waves-light">{{\Lang::get('/common/button.create')}}</button>
                            </div>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
@endsection
@section('styles')
{{ Basset::show('package_uploadifive.css') }}
<link type="text/css" rel="stylesheet" href="{{ asset('newassets/packages/kendo-ui/kendo-ui.material.min.css') }}" />
@endsection

@section('scripts')
{{ Basset::show('package_uploadifive.js') }}
<script type="text/javascript" src="{{ asset('newassets/packages/kendo-ui/kendoui_custom.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $(".datetimepicker").kendoDateTimePicker({
            format: 'yyyy-MM-dd hh:mm:ss',
            value: new Date()
        });
        $(document).on("click", "a.delete-file",function(e){
            e.preventDefault();
            if(e.handled !== 1)
            {
                url = $(this).data('remote');
                parent = $(this).closest('.thumbnail').parent();
                $.getJSON(url, function(){
                    parent.remove();
                });
                e.handled = 1;
            }
        });
        $('#file-items').load($('#file-items').data('url'), function(){});

        $('#file_upload').uploadifive({
            'width': 'auto',
            'buttonClass'   : 'btn btn-primary',
            'buttonText'   : '{{Lang::get('common/general.add_file')}}',
            'multi'         : true,
            'formData'      : {
                'timestamp' : '{{strtotime('now')}}',
                '_token'    : '{{csrf_token()}}'
            },
            'removeCompleted' : true,
            'uploadScript'    : $("#file_upload").data('remote'),
            'onQueueComplete' : function() {
                $('#file-items').load($('#file-items').data('url'), function(){});
            }
        });
    })
</script>
@endsection
