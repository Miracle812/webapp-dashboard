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
                                        <input name="name" type="text" value="{{Input::old('name', null)}}" placeholder="{{\Lang::get('/common/general.name')}}" class="form-control">
                                    </div>

                                    <div class="col-sm-6">
                                        <label class="font-bold">{{\Lang::get('/common/general.target_type')}}</label>
                                        <select name="target_type" class="form-control">
                                            <?php $targetTypes = ['creating_users','creating_units','pods_temps']; ?>
                                            @foreach($targetTypes as $targetType)
                                                <option @if(Input::old('target_type', null)==$targetType) selected @endif value="{{$targetType}}">{{\Lang::get('/common/general.'.$targetType)}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <section id="form_partial">
                            </section>

                            <div class="form-group  m-t">
                                <div class="col-sm-6">
                                    <label  class="font-bold">Send on email</label>
                                    <div class="checkbox i-checks">
                                        <label>
                                            <input name="on_email" type="checkbox" @if(Input::old('on_email', 1)) checked="" @endif><i></i> {{\Lang::get('/common/general.on_email')}}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label  class="font-bold">Send on sms</label>
                                    <div class="checkbox i-checks">
                                        <label>
                                            <input name="on_sms" type="checkbox" @if(Input::old('on_sms', null)) checked="" @endif><i></i> {{\Lang::get('/common/general.on_sms')}}
                                        </label>
                                    </div>
                                </div>
                            </div>

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
        updateSlider = function($target,$val){
            var $max;
            switch($val){
                case 'hours' :  $max = 24; break;
                case 'days' :   $max = 30; break;
                case 'weeks' :  $max = 4; break;
                case 'months' : $max = 12; break;
            }
            $target.data('slider').max = $max;
            $target.slider('setValue', 1);
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
                updateSlider($freqSlider, $fTypeVal);

                $dTypeVal = $("select[name=delay_type] :selected").val();
                if ($dTypeVal != 'none') {
                    $("#delay-selector").removeClass('hide');
                    resizeSlider();
                    updateSlider($delaySlider, $dTypeVal);
                }
                else {
                    $("#delay-selector").addClass('hide');
                }

                $("select[name=freq_type]").on('change', function () {
                    $val = $(this).val();
                    updateSlider($freqSlider, $val);
                    $('input[name=freq_value]').val(1);
                });
                $("select[name=delay_type]").on('change', function () {
                    $val = $(this).val();
                    if ($val != 'none') {
                        $("#delay-selector").removeClass('hide');
                        resizeSlider();
                        $("#delay-selector .tooltip").css('top','-30px');
                        updateSlider($delaySlider, $val);
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
            $("select[name=target_type] option:first").attr('selected',function(){
                $('section#form_partial').load("{{URL::to('/auto-messages/load-form-part')}}/"+($(this).val()),function(data){
                    initSlider();
                    return 'selected';
                });
            });
            $('select[name=target_type]').on('change', function(){
                $selected = $(this).val();
                $('section#form_partial').load("{{URL::to('/auto-messages/load-form-part')}}/"+$selected,function(data){
                    initSlider();
                });
            });
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