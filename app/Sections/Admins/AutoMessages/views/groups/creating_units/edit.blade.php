@extends('_admin.layouts.admin')
@section('title')
    @parent
    :: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')
    <div class="m-b-md">
        <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>{{$sectionName}}
            <span class="pull-right">
                <a class="btn btn-green" href="{{URL::to("/auto-messages")}}"><i class="fa fa-search"></i> {{Lang::get('common/general.groups-list')}} </a>
            </span>
        </h3>
    </div>
    @include('breadcrumbs',['data' => $breadcrumbs])
    <div class="row">
        <div class="col-sm-12">
            <section class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <form role="form" action="{{URL::to('/auto-messages/groups/create')}}" method="post">
                            <div class="form-group col-sm-12">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label class="font-bold">{{\Lang::get('/common/general.name')}}</label>
                                        <input name="name" type="text" value="{{Input::old('name', $group->name)}}" placeholder="{{\Lang::get('/common/general.name')}}" class="form-control">
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="font-bold">{{\Lang::get('/common/general.target_type')}}</label>
                                        <h4>{{$group->target_type}}</h4>
                                    </div>
                                </div>
                            </div>
                            <section id="form_partial">
                                <div id="creating_induce">
                                    <div class="form-group col-sm-12">
                                        <label class="font-bold">{{\Lang::get('/common/general.frequency')}}</label>
                                        <label class="clear">The interval between sending the next messages. (This feature will work when amount of messages will be greater than 1.)</label>
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <label class="font-bold">{{\Lang::get('/common/general.freq_type')}}</label>
                                                <select class="form-control" name="freq_type">
                                                    <?php $timeTypes = ['hours','days','weeks','months']; ?>
                                                    @foreach($timeTypes as $timeType1)
                                                        <option @if(Input::old('freq_type', $group->freq_type) == $timeType1) selected="selected" @endif value="{{$timeType1}}">{{ucfirst($timeType1)}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-sm-9">
                                                <div class="">
                                                    <label class="font-bold">{{\Lang::get('/common/general.by')}}</label>
                                                </div>
                                                <div class="col-sm-9 m-t" id="freq_value_parent">
                                                    <input name="slider_freq_value" class="slider form-control" type="text" value="{{Input::old('freq_value', $group->freq_value)}}" data-slider-min="1" data-slider-max="12" data-slider-step="1" data-slider-value="{{$group->freq_value}}" id="freq_slider" >
                                                </div>
                                                <div class="col-sm-3">
                                                    <input value="{{Input::old('freq_value', $group->freq_value)}}" name="freq_value" type="text" class="form-control text-right" readonly="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-12">
                                        <label class="font-bold">{{\Lang::get('/common/general.delay')}}</label>
                                        <label class="clear">Delay sending of the first message after: creating new data record / an incident appearing / other inducing action depending on the section.</label>
                                        <div class="row">
                                            <div class="col-sm-3 {{{ $errors->has('delay_type') ? 'has-error' : '' }}}">
                                                <label  class="font-bold">{{\Lang::get('/common/general.delay_type')}}</label>
                                                <?php $interval = ['hours'=>12,'days'=>30,'weeks'=>4,'months'=>12]; ?>
                                                <select class="form-control" name="delay_type">
                                                    <option @if(Input::old('delay_type', $group->delay_type) == 'none') selected="selected" @endif value="none" selected="selected">Don't delay.</option>
                                                    @foreach($timeTypes as $timeType2)
                                                        <option @if(Input::old('delay_type', $group->delay_type) == $timeType2) selected="selected" @endif value="{{$timeType2}}">{{ucfirst($timeType2)}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-sm-9 @if(Input::old('delay_type', $group->delay_type) == 'none') hide @endif" id="delay-selector">
                                                <div class="">
                                                    <label class="font-bold">{{\Lang::get('/common/general.to')}}</label>
                                                </div>
                                                <div class="col-sm-9 m-t" id="delay_value_parent">
                                                    <input name="slider_delay_value" class="slider form-control" type="text" value="{{Input::old('delay_value', $group->delay_value)}}" data-slider-min="1" data-slider-max="12" data-slider-step="1" data-slider-value="{{Input::old('delay_value', $group->delay_value)}}" id="delay_slider" >
                                                </div>
                                                <div class="col-sm-3">
                                                    <input value="{{Input::old('delay_value', $group->delay_value)}}" name="delay_value" type="text" class="form-control text-right" readonly="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <label  class="font-bold">{{\Lang::get('/common/general.sending_hour')}}</label>
                                                <input name="send_hour" type="text" value="{{Input::old('send_hour', $group->send_hour)}}" placeholder="{{\Lang::get('/common/general.send_hour')}}" class="form-control datetimepicker">
                                            </div>
                                            <div class="col-sm-3">
                                                <label  class="font-bold">Sending at weekends</label>
                                                <div class="checkbox i-checks">
                                                    <label>
                                                        <input name="weekends" type="checkbox" @if(Input::old('weekends', $group->weekends)) checked="" @endif><i></i> {{\Lang::get('/common/general.include_weekends')}}
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <label  class="font-bold">Send on email</label>
                                                <div class="checkbox i-checks">
                                                    <label>
                                                        <input name="on_email" type="checkbox" @if(Input::old('on_email', $group->on_email)) checked="" @endif><i></i> {{\Lang::get('/common/general.on_email')}}
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <label  class="font-bold">Send on sms</label>
                                                <div class="checkbox i-checks">
                                                    <label>
                                                        <input name="on_sms" type="checkbox" @if(Input::old('on_sms', $group->on_sms)) checked="" @endif><i></i> {{\Lang::get('/common/general.on_sms')}}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                            <div class="form-group col-sm-12 text-center">
                                <button class="btn btn-success" type="submit">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
@section('css')
    {{HTML::style('assets/js/slider/slider.css')}}
    {{Basset::show('package_datetimepicker.css')}}
@endsection
@section('js')
    {{HTML::script('assets/js/slider/bootstrap-slider.js')}}
    {{HTML::script('assets/packages/datetimepicker/js/moment.js')}}
    {{HTML::script('assets/packages/datetimepicker/js/bootstrap-datetimepicker.js')}}
    <script>
        resizeSlider = function(){
            $('#delay_value_parent .slider').width($('#delay_value_parent').width());
            $('#freq_value_parent .slider').width($('#freq_value_parent').width());
            $('#amount_value_parent .slider').width($('#amount_value_parent').width());
        };
        updateSlider = function($target,$val,$type){
            var $max;
            switch($val){
                case 'hours' :  $max = 24; break;
                case 'days' :   $max = 30; break;
                case 'weeks' :  $max = 4; break;
                case 'months' : $max = 12; break;
            }
            $target.data('slider').max = $max;
            $value = ($type == 'update') ? 1 : $target.data('slider').value[0];
            $target.slider('setValue', $value);
        };
        initSlider = function()
        {
            var $amountSlider  = $('#amount_slider');
            var $delaySlider   = $('#delay_slider');
            var $freqSlider    = $('#freq_slider');
            if($amountSlider.length) {
                $amountSlider.slider().on('slideStop', function (ev) {
                    $('input[name=amount_value]').val(ev.value);
                });
            }
            if($delaySlider.length || $freqSlider.length)
            {
                $(".datetimepicker").datetimepicker({
                    pickDate: false,
                    pickTime: true,
                    format:'HH:mm',
                    defaultDate: "10:00"
                });
                $delaySlider.slider().on('slideStop', function (ev) {
                    $('input[name=delay_value]').val(ev.value);
                });
                $freqSlider.slider().on('slideStop', function (ev) {
                    $('input[name=freq_value]').val(ev.value);
                });
                $fTypeVal = $("select[name=freq_type] :selected").val();
                updateSlider($freqSlider, $fTypeVal,'init');
                $dTypeVal = $("select[name=delay_type] :selected").val();
                if ($dTypeVal != 'none') {
                    $("#delay-selector").removeClass('hide');
                    resizeSlider();
                    updateSlider($delaySlider,$dTypeVal,'init');
                }
                else {
                    $("#delay-selector").addClass('hide');
                }
                $("select[name=freq_type]").on('change', function () {
                    $val = $(this).val();
                    updateSlider($freqSlider,$val,'update');
                    $('input[name=freq_value]').val(1);
                });
                $("select[name=delay_type]").on('change', function () {
                    $val = $(this).val();
                    if ($val != 'none') {
                        $("#delay-selector").removeClass('hide');
                        resizeSlider();
                        $("#delay-selector .tooltip").css('top','-30px');
                        updateSlider($delaySlider, $val,'update');
                        $('input[name=delay_value]').val(1);
                    }
                    else {
                        $("#delay-selector").addClass('hide');
                    }
                });
            }
            resizeSlider();
        };
        $(function()
        {
            initSlider();
        });
        $("form").on('submit', function(e){
            if(e.handled == 1){
                e.handled = 1;
                return false;
            }
            e.preventDefault();
            var form = $(this);
            data = form.serialize();
            url = form.data('action');
            $.ajax({
                context: { element: form },
                url: url,
                type: "post",
                dataType: "json",
                data:data,
                success:function(msg) {
                    if(msg.type=='success')
                        top.location.href = "{{URL::to('/auto-messages')}}";
                }
            });
        });
        window.addEventListener('resize', function(event){
            resizeSlider();
        });
    </script>
@endsection