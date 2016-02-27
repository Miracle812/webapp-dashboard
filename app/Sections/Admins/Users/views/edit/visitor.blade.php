<div class="form-group">
    <label class="col-sm-2 control-label">{{\Lang::get('/common/general.headquarter')}}</label>
    <div class="col-sm-10">
        {{\Form::select('headquarter', $headquarters->lists('name','id'), $user->headquarters ? $user->headquarters->lists('id') : null, ['class'=>'form-control'])}}
    </div>
</div>
<div id="upload-units-field"></div>
<div class="form-group">
    <label class="col-sm-2 control-label">{{\Lang::get('/common/general.expiry_date')}}</label>
    <div class="col-sm-10">
        <input name="expiry_date" type="text" class="form-control datetimepicker" placeholder="{{Lang::get('common/general.expiry_date')}}" value="{{Input::old('expiry_date', $user->assigned_expiry_date ? \Carbon::createFromFormat('Y-m-d H:i:s',$user->assigned_expiry_date->expiry_date)->format('Y-m-d') : \Carbon::now()->addDays(7)->format('Y-m-d'))}}">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">{{\Lang::get('/common/general.timezone')}}</label>
    <div class="col-sm-10">
        {{Form::select('timezone', $timezonesArray, $user->timezone, ['class'=>'form-control'])}}
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">{{\Lang::get('/common/general.first_name')}}</label>
    <div class="col-sm-10">
        <input name="first_name" type="text" class="form-control" placeholder="{{Lang::get('common/general.first_name')}}" value="{{Input::old('first_name', $user->first_name)}}">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">{{\Lang::get('/common/general.surname')}}</label>
    <div class="col-sm-10">
        <input name="surname" type="text" class="form-control" placeholder="{{Lang::get('common/general.surname')}}" value="{{Input::old('surname', $user->surname)}}">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">{{\Lang::get('/common/general.email')}}</label>
    <div class="col-sm-10">
        <input name="email" type="text" class="form-control" placeholder="{{Lang::get('common/general.email')}}" value="{{Input::old('email', $user->email)}}">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">{{\Lang::get('/common/general.mobile_phone')}}</label>
    <div class="col-sm-10">
        <input name="mobile_phone" type="text" class="form-control" placeholder="{{Lang::get('common/general.mobile_phone')}}" value="{{Input::old('mobile_phone', $user->mobile_phone)}}">
    </div>
</div>