@extends('newlayout.modals.modal')
@section('title')
{{$sectionName}} - {{$actionName}}
@endsection
@section('content')
<form data-url="{{URL::to("/check-list/create")}}">

    <div class="uk-grid">
        <div class="uk-width-1-1">
            <label>{{Lang::get('common/general.title')}}:</label>
            <input type="text" name="title" id="title" class="md-input" >
        </div>
    </div>
    <div class="uk-grid">
        <div class="uk-width-1-1">
            <label>{{Lang::get('common/general.description')}}:</label>
            <textarea name="description" class="md-input" ></textarea>
        </div>
    </div>
    <div class="uk-grid">
        @if($staff->count())
        <div class="uk-width-1-3" id="assign_to_staff">
            <label>{{Lang::get('common/general.assign_staff')}}:</label>
            <select data-md-selectize name="staff_id">
                <option value="null">Don't assign</option>
                @foreach($staff as $value)
                    <option value="{{$value->id}}">{{$value->fullname()}}</option>
                @endforeach
            </select>
        </div>
        @endif
        @if($forms->count())
        <div class="uk-width-2-3" id="assign_to_form">
            <label>{{Lang::get('common/general.assign_form')}}:</label>
            <select data-md-selectize name="form_id">
                <option value="null">Don't assign</option>
                @foreach($forms as $form)
                    <option value="{{$form->id}}">{{$form->name}}</option>
                @endforeach
            </select>
        </div>
        @endif
    </div>
    <div class="uk-grid">
        <div class="uk-width-1-4">
            <label>{{Lang::get('common/general.type')}}</label>
            <?php $class = ['default'=>'navitas','high'=>'danger','medium'=>'primary','low'=>'warning'];?>
            <select data-md-selectize name="type">
            @foreach($class as $key => $val)
                <option value="{{$key}}">{{Lang::get('common/general.'.$key)}}</option>
            @endforeach
            </select>
        </div>
        <div class="uk-width-3-4">
            <div class="uk-grid">
                <div class="uk-width-1-2 repeat-type">
                    <label>{{Lang::get('common/general.repeat')}}: </label>
                    <?php $class = ['none'=>'dont_repeat','day'=>'daily','week'=>'weekly','month'=>'monthly'];?>
                    <select data-md-selectize name="type">
                    @foreach($class as $key => $val)
                        <option value="{{$key}}">{{Lang::get('common/general.'.$val)}}</option>
                    @endforeach
                    </select>
                </div>
                <div class="uk-width-1-2 repeat-frequency" style="display: none">
                    <label>{{Lang::get('common/general.every')}}: </label>
                    <select data-md-selectize name="type">
                    @for($i=1; $i<7; $i++)
                        <option value="{{$i}}">{{$i}}</option>
                    @endfor
                    </select>
                    <div class="uk-margin-small-top freq_type"></div>
                </div>
            </div>
            <div class="uk-grid" id="limit_range" style="display: none">
                <div class="uk-width-1-2">
                    <label>{{Lang::get('common/general.repeat_to')}}:</label>
                    <input type="text" name="repeat_to" id="repeat_to" value="{{$data['e']->format('Y-m-d')}}" class="datetimepicker" style="width:100%">
                </div>
                <div class="uk-width-1-2">
                    <label>&nbsp;</label>
                    <div class="uk-input-wrapper">
                        <input type="checkbox" value="1" name="weekend" id="work_at_weekend" data-md-icheck> <label for="work_at_weekend">Work at the weekend?</label>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <hr class="md-hr" />

    <div class="uk-form-row uk-text-right">
        <a href="#" class="md-btn md-btn-default uk-modal-close" id="resetButton">{{Lang::get('common/button.cancel')}}</a>
        <button type="submit" id="submitButton" class="md-btn md-btn-success" >{{Lang::get('common/button.create')}}</button>
    </div>
</form>
@endsection
@section('styles')
<link type="text/css" rel="stylesheet" href="{{ asset('newassets/packages/kendo-ui/kendo-ui.material.min.css') }}" />
<style>
    .uk-modal-dialog {max-width: 600px}
</style>
@endsection
@section('scripts')
    <script type="text/javascript" src="{{ asset('newassets/packages/kendo-ui/kendoui_custom.min.js') }}"></script>

    <script>
    $(document).ready(function(){

        $(".datetimepicker").kendoDateTimePicker({
            format: 'yyyy-MM-dd',
            value: new Date()
        });

        altair_md.init('.uk-modal-dialog');
        altair_forms.init('.uk-modal-dialog');

        var $checked = $("input[name=repeat]:checked");
        if( $checked.val() !== 'none' ) {
            $("span.freq_type").text($checked.val()+'(s)');
        }
        else {
            $(".repeat-frequency,#limit_range").hide();
        }
        $(".repeat-type select").on('change', function() {
            var $input = $(this);
            if($input.val()!=='none'){
                $(".repeat-frequency,#limit_range").show();
                $("span.freq_type").text($input.val()+'(s)');
            }
            else{
                $(".repeat-frequency, #limit_range").hide();
                $("span.freq_type").text('');
            }
        });
        var form = $('.uk-modal form');
        form.on('submit', function(){
            doSubmit();
            return false;
        });
        function doSubmit(){
            calendar    = $('.calendar');
            var data = form.serializeArray();
            data.push({
                name: 'start',
                value: '{{$data['s']}}'},{
                name: 'end',
                value: '{{$data['e']}}'},{
                name: 'all_day',
                value: '{{$data['d']}}'
            });
            $.ajax({
                context: { element: form },
                url: form.data('url'),
                data: data,
                type: "POST",
                success: function(data){
                    if(data.type == 'success'){
                        $('.calendar').fullCalendar('refetchEvents');
                        $('#ajaxModal').data('modal').hide();
                    } else {
                        notifyResponse(data);
                    };
                }
            });
        };
    });
</script>
@endsection