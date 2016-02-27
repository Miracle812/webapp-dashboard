@extends('_default.modals.modal')
@section('title')
{{$sectionName}} - {{$actionName}}
@endsection
@section('content')
<form class="form-horizontal" data-url="{{URL::to("/new-cleaning-schedule/create")}}">
    <div class="row">
        <div class="col-sm-12" >
            <div class="form-group">
                <div class="col-sm-12">
                    <label class="control-label">{{Lang::get('common/general.title')}}:</label>
                    <input type="text" name="title" id="title" placeholder="{{Lang::get('common/general.title')}}" class="form-control" >
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <label class="control-label">{{Lang::get('common/general.description')}}:</label>
                    <textarea name="description" class="form-control" placeholder="{{Lang::get('common/general.description')}}"></textarea>
                </div>
            </div>
            <div class="line line-dashed b-b line-lg pull-in"></div>
            <div class="form-group">
                <div class="col-sm-4" id="assign_to_staff">
                    <label class="control-label">{{Lang::get('common/general.staff')}}:</label>
                    <select class="form-control" name="staff_id">
                        <option value="null">@if($staff->count()) Don't assign @else Not available.@endif</option>
                        @foreach($staff as $value)
                            <option value="{{$value->id}}">{{$value->fullname()}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-8" id="assign_to_form">
                    <label class="control-label">Form:</label>
                    <select class="form-control" name="form_id">
                        <option value="null">@if($forms->count()) Don't assign @else Not available.@endif</option>
                        @foreach($forms as $form)
                            <option value="{{$form->id}}">{{$form->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="line line-dashed b-b line-lg pull-in"></div>
            <div class="form-group">
                <div class="col-sm-2">
                    <label class="control-label">All day?</label>
                    <div id="icheck_all_day" class="icheckbox">
                        <label>
                            <input name="all_day" type="checkbox" @if($data['d']) checked @endif><i></i>
                        </label>
                    </div>
                </div>
                <div id="dates_area"> </div>
                <div class="col-sm-2 m-l-n">
                    <label class="col-sm-12">Repeatable?</label>
                    <div class="col-sm-12">
                        <input name="is_repeatable" type="checkbox" class="js-switchery"/>
                    </div>
                </div>
            </div>
            <div id="repeat_options" class="hidden">
                <div class="line line-dashed b-b line-lg pull-in"></div>
                <div class="form-group">
                    <div class="col-sm-12 repeat-type">
                        <label class="col-sm-2 control-label m-l-n">Repeat </label>
                        <div class="col-sm-3">
                            <select name="repeat" class="form-control" id="freq_type">
                                <?php $class = ['day'=>'daily','week'=>'weekly','month'=>'monthly','year'=>'yearly'];?>
                                @foreach($class as $key => $val)
                                    <option @if($key=='day') checked="checked" @endif value="{{$key}}">{{Lang::get('common/general.'.$val)}}</option>
                                @endforeach
                            </select>
                        </div>
                        <label class="col-sm-1 control-label">every</label>
                        <div class="col-sm-2">
                            <input type="text" name="repeat_every" id="title" value="1" class="form-control">
                        </div>
                        <label class="col-sm-1 control-label freq_type">day(s)</label>
                    </div>
                </div>
                <div class="line line-dashed b-b line-lg pull-in"></div>
                <div class="form-group">
                    <div class="col-sm-7">
                        <label class="col-sm-4 control-label m-l-n">Repeat until </label>
                        <div class="col-sm-8">
                            <input type="text" name="repeat_until" id="title" value="{{$data['e']->copy()->addWeeks(1)->format('d/m/Y')}}" placeholder="Repeat until" class=" datepicker inline m-l " >
                        </div>
                    </div>

                    <div class="col-sm-5">
                        <div class="col-sm-12">
                            <div id="icheck_weekends" class="icheckbox col-sm-1 m-r">
                                <label>
                                    <input id="icheck_weekends" name="weekends" type="checkbox" checked ><i></i>
                                </label>
                            </div>
                            <label for="icheck_weekends" class="icheckbox">Repeat at weekend?</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-default" id="resetButton" data-dismiss="modal">{{Lang::get('common/button.cancel')}}</button>
        <button type="submit" id="submitButton" class="btn btn-green" >{{Lang::get('common/button.create')}}</button>
    </div>
</form>
<script id="dates_area_allday_not" type="text/ng-template">
    <div class="col-sm-4">
        <label class="control-label">Start:</label>
        <input type="text" name="start" id="title" value="{{$data['s']->format('d/m/Y H:i')}}" placeholder="Start" class="form-control datetimepicker" >
    </div>
    <div class="col-sm-4">
        <label class="control-label">End:</label>
        <input type="text" name="end" id="title" value="{{$data['e']->format('d/m/Y H:i')}}" placeholder="End" class="form-control datetimepicker" >
    </div>
</script>
<script id="dates_area_allday"  type="text/ng-template">
    <div class="col-sm-4">
        <label class="control-label">Date task</label>
        <input type="text" name="start" id="title" value="{{$data['s']->format('d/m/Y')}}" placeholder="Date task" class="form-control datepicker" >
    </div>
</script>
@endsection
@section('css')
    <link href="/newassets/packages/jquery-icheck/skins/flat/orange.css" rel="stylesheet">
    <link href="/newassets/packages/datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
    <link href="/newassets/packages/switchery/dist/switchery.min.css" rel="stylesheet">

<style>
    .modal-dialog {width: 600px}
</style>
@endsection
@section('js')
    <script src="/newassets/packages/jquery-icheck/icheck.min.js"></script>
    <script src="/newassets/packages/datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
    <script src="/newassets/packages/switchery/dist/switchery.min.js"></script>

    <script>
        var schedules_creator = {
            init: function () {
                'use strict';
                schedules_creator.ichecks();
                schedules_creator.task_dates_type();
                schedules_creator.datepickers();
                schedules_creator.switchery();
                schedules_creator.spelling();
                schedules_creator.move_weekends();

            },
            ichecks: function () {
                $('.icheckbox input').iCheck({
                    checkboxClass: 'icheckbox_flat-orange',
                    radioClass: 'iradio_flat-orange'
                });
            },
            datepickers: function () {
                $(".datetimepicker").datetimepicker({
                    format: 'DD/MM/YYYY H:mm',
                });
                $(".datepicker").datetimepicker({
                    format: 'DD/MM/YYYY',
                });
            },
            switchery: function () {
                var switchery = new Switchery($('.js-switchery')[0]);
                $('.js-switchery').on('change',function(e){

                    if ($(this).is(':checked')) {
                        $('#repeat_options').removeClass('hidden')
                    } else {
                        $('#repeat_options').addClass('hidden')
                    }
                })
            },
            move_weekends: function ()
            {

            },
            spelling: function () {
                $selector = $("select[name=repeat]");
                $("label.freq_type").text($selector.val()+'(s)');
                $selector.on('change',function(){
                    $("label.freq_type").text($(this).val()+'(s)');
                });
            },
            task_dates_type: function () {
                $selector = $('input[name=all_day]');
                $content = $selector.prop('checked') ? $('#dates_area_allday').html() : $('#dates_area_allday_not').html();
                $("#dates_area").html($content)
                $('input[name=all_day]')
                        .on('ifChecked', function(event){
                            $content = $('#dates_area_allday').html();
                            $("#dates_area").html($content);
                            schedules_creator.datepickers();
                        })
                        .on('ifUnchecked', function(event){
                            $content =  $('#dates_area_allday_not').html();
                            $("#dates_area").html($content);
                            schedules_creator.datepickers();

                        }).on('ifChanged', function(event){
                    $('#dates_area_allday_not, #dates_area_allday').hide();
                });
            }
        };
        $(function() {
            schedules_creator.init();

            var form = $('.modal form');
            form.on('submit', function(){
                doSubmit();
                return false;
            });
            function doSubmit(){
                calendar    = $('.calendar');
                var data = form.serializeArray();
                $.ajax({
                    context: { element: form },
                    url: form.data('url'),
                    data: data,
                    type: "POST",
                    success: function(data){
                        if(data.type == 'success'){
                            $('.calendar').fullCalendar('refetchEvents');
                            //$('#ajaxModal').modal('hide');
                        };
                    }
                });
            };
        });
    </script>








@endsection