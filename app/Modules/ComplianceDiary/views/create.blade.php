<button class="uk-modal-close uk-close uk-float-right" type="button"></button>
<h2 class="heading_b uk-margin-bottom">Create task</h2>
    <form id="compliance_diary_form" class="form-horizontal" data-url="{{URL::to("/compliance-diary/create")}}">
        <div class="uk-grid">
            <div class="uk-width-1-1 uk-form-row">
                <div id="colors_picker"></div>
            </div>
            <div class="uk-width-1-1 uk-form-row">
                <label>Task name</label>
                <input name="title" type="text" class="md-input">
            </div>
            <div class="uk-width-1-1 uk-form-row">
                <label>Description</label>
                <textarea name="description" class="md-input autosize_init" rows="4" cols="30"></textarea>
            </div>
            {{--
            <div class="uk-width-1-1 uk-form-row">
                <select @if(!$forms->count()) disabled @endif name="form_id" data-md-selectize>
                    <option value="null">@if($forms->count()>1) Don't assign @else Forms are not available.@endif</option>
                    @foreach($forms as $form)
                        <option value="{{$form->id}}">{{$form->name}}</option>
                    @endforeach
                </select>
            </div>
            --}}
            <div class="uk-width-1-1 uk-form-row">
                <div class="uk-grid">
                    <div class="uk-width-1-4 uk-form-item">
                        <span class="icheck-inline">
                            <input type="checkbox" data-md-icheck id="checkbox_all_day" name="all_day" @if($data['s']->format('d/m/Y') == $data['e']->format('d/m/Y')) checked @endif>
                            <label class="inline-label" for="checkbox_all_day">All day</label>
                        </span>
                    </div>
                    <div class="uk-width-2-4">
                        <div id="dates_area" class="uk-grid"></div>
                    </div>
                    <div class="uk-width-1-4 uk-form-item">
                        <span class="inline">
                            <input type="checkbox"  id="checkbox_is_repeatable" name="is_repeatable" class="js-switchery">
                            <label class="inline-label" for="checkbox_is_repeatable">Repeat?</label>
                        </span>
                    </div>
                </div>
            </div>
            <div class="uk-width-1-1 uk-form-row uk-hidden" id="repeat_options">
                <label class="uk-width-1-1">Repeat</label>
                <div class="uk-grid">
                    <div class="uk-width-2-6 uk-form-item">
                        <select name="repeat" data-md-selectize>
                            @foreach(['day'=>'daily','week'=>'weekly','month'=>'monthly','year'=>'yearly'] as $key => $val)
                                <option @if($key=='day') checked="checked" @endif value="{{$key}}">{{Lang::get('common/general.'.$val)}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="uk-width-4-6">
                        <div class="uk-grid">
                            <div class="uk-width-1-6"><label class="uk-margin-top uk-form-help-block">every</label></div>
                            <div class="uk-width-3-6 uk-form-item"><input type="text" name="repeat_every" value="1" class="md-input uk-form-help-inline"></div>
                            <div class="uk-width-2-6"><label class="uk-margin-top uk-form-help-block freq_type">day(s)</label></div>
                        </div>
                    </div>
                    <div class="uk-width-1-1 uk-margin-top">
                        <div class="uk-grid">
                            <div class="uk-width-3-6 uk-form-item">
                                <label>Repeat until</label>
                                <input value="{{$data['e']->copy()->addWeeks(1)->format('d/m/Y')}}" type="text" name="repeat_until" class="md-input" data-uk-datepicker="{format:'DD/MM/YYYY'}">
                            </div>
                            <div class="uk-width-3-6 uk-form-item uk-margin-top">
                                <span class="icheck-inline">
                                    <input type="checkbox" data-md-icheck id="icheck_weekends" name="weekends">
                                    <label class="inline-label" for="icheck_weekends">Repeat at weekend?</label>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="uk-modal-footer uk-text-right">
            <button id="resetButton" class="md-btn md-btn-flat uk-modal-close">{{Lang::get('common/button.cancel')}}</button>
            <button type="submit" id="submitButton" class="md-btn  md-btn md-btn-success md-btn-flat" >{{Lang::get('common/button.create')}}</button>
        </div>
    </form>

<!--temlpates-->
    <script id="dates_area_allday_not" type="text/ng-template">
        <div class="uk-form-item uk-width-1-2">
            <label>Start:</label>
            <input value="{{$data['s']->format('d/m/Y')}}" type="text" name="start" placeholder="Start" class="md-input" data-uk-datepicker="{format:'DD/MM/YYYY'}">
        </div>
        <div class="uk-form-item uk-width-1-2">
            <label>End:</label>
            <input value="{{$data['e']->format('d/m/Y')}}" type="text" name="end" placeholder="End" class="md-input" data-uk-datepicker="{format:'DD/MM/YYYY'}">
        </div>
    </script>
    <script id="dates_area_allday"  type="text/ng-template">
        <div class="uk-form-item uk-width-1-1">
            <label class="k">Date task</label>
            <input value="{{$data['s']->format('d/m/Y')}}" type="text" name="start" placeholder="Date task" class="md-input" data-uk-datepicker="{format:'DD/MM/YYYY'}">
        </div>
    </script>
<!--temlpates-->
<!--scripts-->
    <script>
        var schedules_creator = {
            init: function () {
                'use strict';
                schedules_creator.ichecks();
                schedules_creator.colorspicker();
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
            colorspicker: function () {
                var calendarColorsWrapper = $('<div id="calendar_colors_wrapper"></div>');
                var calendarColorPicker = altair_helpers.color_picker(calendarColorsWrapper).prop('outerHTML');
                $('#colors_picker').append('Task color:' +  calendarColorPicker);
                $('#colors_picker').find('[data-color=#f4511e]').addClass('active_color');
                $('#colors_picker').find('input').attr('name','task_color').val('#f4511e');
            },
            datepickers: function ($selector) {
                altair_md.init($selector);
            },
            switchery: function () {
                var switchery = new Switchery($('.js-switchery')[0]);
                $('.js-switchery').on('change',function(e){
                    if ($(this).is(':checked')) {
                        $('#repeat_options').removeClass('uk-hidden')
                    } else {
                        $('#repeat_options').addClass('uk-hidden')
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
                    schedules_creator.datepickers($("#dates_area").html($content));
                    $('input[name=all_day]')
                        .on('ifChecked', function(event){
                            $content = $('#dates_area_allday').html();
                            schedules_creator.datepickers($("#dates_area").html($content));
                        })
                        .on('ifUnchecked', function(event){
                            $content =  $('#dates_area_allday_not').html();
                                schedules_creator.datepickers($("#dates_area").html($content));
                        }).on('ifChanged', function(event){
                    $('#dates_area_allday_not, #dates_area_allday').hide();
                });
            }
        };
        $(document).ready(function(){
            schedules_creator.init();
            var form = $('form#compliance_diary_form');
            var $modal = form.parents('div.uk-modal');
            form.on('submit', function(){
                doSubmit();
                return false;
            });
            function doSubmit(){
                calendar    = $('#calendar');
                var data = form.serializeArray();
                $.ajax({
                    context: { element: form },
                    url: form.data('url'),
                    data: data,
                    type: "POST",
                    success: function(data){
                        if(data.type == 'success'){
                            $modal.remove();
                            $('.uk-notify').remove();
                            $('#calendar').fullCalendar('refetchEvents');
                        };
                    }
                });
            };
        });
    </script>
<!--scripts-->

<!--styles-->
    <style>
        .uk-datepicker,.selectize-dropdown {z-index: 2000;}
    </style>
<!--styles-->