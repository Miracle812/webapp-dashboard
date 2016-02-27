<div id="creating_induce">
    <div class="form-group col-sm-12">
        <label class="font-bold">{{\Lang::get('/common/general.frequency')}}</label>
        <label class="clear">The interval between sending the next messages. (This feature will work when amount of messages will be greater than 1.)</label>
        <div class="row">
            <div class="col-sm-3">
                <label class="font-bold">{{\Lang::get('/common/general.freq_type')}}</label>
                <select class="form-control" name="freq_type">
                    <option value="hours">Hours</option>
                    <option value="days"selected="selected">Days</option>
                    <option value="weeks">Weeks</option>
                    <option value="months">Months</option>
                </select>
            </div>
            <div class="col-sm-9">
                <div class="">
                    <label class="font-bold">{{\Lang::get('/common/general.by')}}</label>
                </div>
                <div class="col-sm-9 m-t" id="freq_value_parent">
                    <input name="slider_freq_value" class="slider form-control" type="text" value="1" data-slider-min="1" data-slider-max="12" data-slider-step="1" data-slider-value="1" id="freq_slider" >
                </div>
                <div class="col-sm-3">
                    <input value="1" name="freq_value" type="text" class="form-control text-right" readonly="">
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
                    <option value="none" selected="selected">Don't delay.</option>
                    <option value="hours">Hours</option>
                    <option value="days">Days</option>
                    <option value="weeks">Weeks</option>
                    <option value="months">Months</option>
                </select>
            </div>
            <div class="col-sm-9 hide" id="delay-selector">
                <div class="">
                    <label class="font-bold">{{\Lang::get('/common/general.to')}}</label>
                </div>
                <div class="col-sm-9 m-t" id="delay_value_parent">
                    <input name="slider_delay_value" class="slider form-control" type="text" value="1" data-slider-min="1" data-slider-max="12" data-slider-step="1" data-slider-value="1" id="delay_slider" >
                </div>
                <div class="col-sm-3">
                    <input value="1" name="delay_value" type="text" class="form-control text-right" readonly="">
                </div>
            </div>
        </div>
    </div>
    <div class="form-group col-sm-12">
        <div class="row">
            <div class="col-sm-6">
                <label  class="font-bold">{{\Lang::get('/common/general.sending_hour')}}</label>
                <input name="send_hour" type="text" value="{{Input::old('send_hour', null)}}" placeholder="{{\Lang::get('/common/general.send_hour')}}" class="form-control datetimepicker">
            </div>
            <div class="col-sm-6">
                <label  class="font-bold">Sending at weekends</label>
                <div class="checkbox i-checks">
                    <label>
                        <input name="weekends" type="checkbox" @if(Input::old('weekends', null))checked=""@endif><i></i> {{\Lang::get('/common/general.include_weekends')}}
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>