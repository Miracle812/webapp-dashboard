<div class="form-group" id="unit-select">
    <label class="col-sm-2 control-label">{{\Lang::get('/common/general.units')}}</label>
    <div class="col-sm-10">
        {{Form::select('units', $units, null, ['class'=>'form-control'])}}
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">{{\Lang::get('/common/general.expiry_date')}}</label>
    <div class="col-sm-10">
        <input name="expiry_date" type="text" class="form-control datetimepicker" placeholder="{{Lang::get('common/general.expiry_date')}}" value="{{Input::old('expiry_date', date('Y-m-d' ,strtotime('7 days')))}}">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">{{\Lang::get('/common/general.timezone')}}</label>
    <div class="col-sm-10">
        {{Form::select('timezone', $timezonesArray, 'Europe/London', ['class'=>'form-control'])}}
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">{{\Lang::get('/common/general.first_name')}}</label>
    <div class="col-sm-10">
        <input name="first_name" type="text" class="form-control" placeholder="{{Lang::get('common/general.first_name')}}" value="{{Input::old('first_name', null)}}">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">{{\Lang::get('/common/general.surname')}}</label>
    <div class="col-sm-10">
        <input name="surname" type="text" class="form-control" placeholder="{{Lang::get('common/general.surname')}}" value="{{Input::old('surname', null)}}">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">{{\Lang::get('/common/general.email')}}</label>
    <div class="col-sm-10">
        <input name="email" type="text" class="form-control" placeholder="{{Lang::get('common/general.email')}}" value="{{Input::old('email', null)}}">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">{{\Lang::get('/common/general.mobile_phone')}}</label>
    <div class="col-sm-10">
        <input name="mobile_phone" type="text" class="form-control" placeholder="{{Lang::get('common/general.mobile_phone')}}" value="{{Input::old('mobile_phone', NULL)}}">
    </div>
</div>