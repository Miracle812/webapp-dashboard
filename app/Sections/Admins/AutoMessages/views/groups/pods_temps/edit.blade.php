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
                                <div id="amount_trigger">
                                    <div class="form-group col-sm-12">
                                        <label class="font-bold">{{\Lang::get('/common/general.amount_trigger')}}</label>
                                        <label class="clear">Message will be send by trigger when amount of incident will be achieved.</label>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="">
                                                    <label class="font-bold">{{\Lang::get('/common/general.amount')}}</label>
                                                </div>
                                                <div class="col-sm-9 m-t" id="amount_value_parent">
                                                    <input name="slider_amount_value" class="slider form-control" type="text" value="{{$group->freq_value}}" data-slider-min="1" data-slider-max="10" data-slider-step="1" data-slider-value="{{$group->freq_value}}" id="amount_slider" >
                                                </div>
                                                <div class="col-sm-3 ">
                                                    <input value="{{$group->freq_value}}" name="amount_value" type="text" class="form-control text-right" readonly="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row m-t">

                                            <div class="col-sm-6">
                                                <label  class="font-bold">Send on email</label>
                                                <div class="checkbox i-checks">
                                                    <label>
                                                        <input name="on_email" type="checkbox" @if(Input::old('on_email', $group->on_email)) checked="" @endif><i></i> {{\Lang::get('/common/general.on_email')}}
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
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
@endsection
@section('js')
    {{HTML::script('assets/js/slider/bootstrap-slider.js')}}
    {{HTML::script('assets/packages/datetimepicker/js/moment.js')}}
    <script>
        resizeSlider = function(){
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
            if($amountSlider.length) {
                $amountSlider.slider().on('slideStop', function (ev) {
                    $('input[name=amount_value]').val(ev.value);
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