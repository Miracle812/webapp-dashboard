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
                    <a class="md-btn md-bg-green-600 md-color-white md-btn-wave-light waves-effect waves-button waves-light" href="{{URL::to("/trainings/list")}}"><i class="material-icons">search</i> {{Lang::get('common/general.datatable')}} </a>
                </span>
            </h2>

            <div class="md-card">

                <div class="md-card-toolbar">
                    <h3 class="md-card-toolbar-heading-text large">
                        {{$sectionName}} - {{$actionName}}
                    </h3>
                </div>

                <div class="md-card-content">
                    <form method="post" action="{{URL::to("/trainings/edit/$training->id")}}">
                        <input type="hidden" name="_token" value="<?= csrf_token() ?>" />
                        <div class="uk-grid">
                            <div class="uk-width-1-3 {{{ $errors->has('staff_id') ? 'has-error' : '' }}}">
                                <div class="md-input-wrapper md-input-filled">
                                    <label>{{Lang::get('common/sections.staff.title')}}</label>
                                    {{Form::select('staff_id', $staff, Input::old('staff_id', $training->staff_id), array('id'=>'staff', 'data-md-selectize'=>''))}}
                                    @if($errors->has('staff_id'))
                                    <div class="uk-text-danger">{{ Lang::get($errors->first('staff_id')) }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="uk-width-1-3 {{{ $errors->has('name') ? 'has-error' : '' }}}">
                                <label>{{Lang::get('common/general.course_name')}}</label>
                                <input type="text" name="name" value="{{{ Input::old('name', $training->name) }}}" class="md-input">
                                @if($errors->has('name'))
                                    <div class="uk-text-danger">{{ Lang::get($errors->first('name')) }}</div>
                                @endif
                            </div>
                            <div class="uk-width-1-3 {{{ $errors->has('address') ? 'has-error' : '' }}}">
                                <label>{{Lang::get('common/general.course_address')}}</label>
                                <input type="text" name="address" value="{{{ Input::old('address', $training->address) }}}" class="md-input">
                                @if($errors->has('address'))
                                    <div class="uk-text-danger">{{ Lang::get($errors->first('address')) }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="uk-grid">
                            <div class="uk-width-1-3 {{{ $errors->has('date_start') ? 'has-error' : '' }}}">
                                <label>{{Lang::get('common/general.date_start')}}</label>
                                <input type="text" name="date_start" value="{{{ Input::old('date_start', \Carbon::createFromTimestamp(strtotime($training->date_start),'UTC')->timezone(\Auth::user()->timezone)->format('Y-m-d')) }}}" class="datetimepicker" style="width:100%">
                                @if($errors->has('date_start'))
                                    <div class="uk-text-danger">{{ Lang::get($errors->first('date_start')) }}</div>
                                @endif
                            </div>

                            <div class="uk-width-1-3 {{{ $errors->has('date_finish') ? 'has-error' : '' }}}">
                                <label>{{Lang::get('common/general.date_finish')}}</label>
                                <input type="text" name="date_finish" value="{{{ Input::old('date_finish', \Carbon::createFromTimestamp(strtotime($training->date_finish),'UTC')->timezone(\Auth::user()->timezone)->format('Y-m-d')) }}}" class="datetimepicker" style="width:100%">
                                @if($errors->has('date_finish'))
                                    <div class="uk-text-danger">{{ Lang::get($errors->first('date_finish')) }}</div>
                                @endif
                            </div>

                            <div class="uk-width-1-3 {{{ $errors->has('date_refresh') ? 'has-error' : '' }}}">
                                <label>{{Lang::get('common/general.date_refresh')}}</label>
                                <input type="text" name="date_refresh" value="{{{ Input::old('date_refresh', \Carbon::createFromTimestamp(strtotime($training->date_refresh),'UTC')->timezone(\Auth::user()->timezone)->format('Y-m-d')) }}}" class="datetimepicker" style="width:100%">
                                @if($errors->has('date_refresh'))
                                    <div class="uk-text-danger">{{ Lang::get($errors->first('date_refresh')) }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="uk-grid">
                            <div class="uk-width-1-1">
                                <label>{{Lang::get('common/general.additional_infos')}}</label>
                                <textarea name="comments" class="md-input">{{{ Input::old('comments', $training->comments) }}}</textarea>
                            </div>
                        </div>

                        <div class="uk-grid">
                            <div class="uk-width-1-1">
                                <?php
                                $targetType = 'training_records';
                                $options = Config::get('files_uploader.'.$targetType);
                                $target = [
                                        'target_type' => $targetType,
                                        'target_id' => $training->id
                                ];
                                ?>
                                {{\FormExt::common_files_uploader($options,$target)}}
                            </div>
                        </div>

                        <hr class="md-hr" />

                        <div class="uk-grid">
                            <div class="uk-width-1-1 uk-text-right">
                                <button class="md-btn md-btn-success">{{Lang::get('common/button.submit')}}</button>
                            </div>
                        </div>
                    </form>
                
                </div>
            </div>

        </div>
    </div>
@endsection


@section('styles')
<link type="text/css" rel="stylesheet" href="{{ asset('newassets/packages/kendo-ui/kendo-ui.material.min.css') }}" />
@endsection

@section('scripts')
<script type="text/javascript" src="{{ asset('newassets/packages/kendo-ui/kendoui_custom.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $(".datetimepicker").kendoDateTimePicker({
            format: 'yyyy-MM-dd'
        });
    })
</script>
@endsection
