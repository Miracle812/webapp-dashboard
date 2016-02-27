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
    <div class="col-sm-6">
        <input name="email" type="text" class="form-control" placeholder="{{Lang::get('common/general.email')}}" value="{{Input::old('email', null)}}">
    </div>
    <div class="checkbox i-checks col-sm-4">
        <label>
            <input name="confirmed" type="checkbox" value="1" >
            <i></i>
            {{\Lang::get('/common/general.confirmed')}}
        </label>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">{{\Lang::get('/common/general.mobile_phone')}}</label>
    <div class="col-sm-10">
        <input name="mobile_phone" type="text" class="form-control" placeholder="{{Lang::get('common/general.mobile_phone')}}" value="{{Input::old('mobile_phone', NULL)}}">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">{{\Lang::get('/common/general.phone')}}</label>
    <div class="col-sm-10 ">
        <input name="phone" type="text" class="form-control" placeholder="{{Lang::get('common/general.phone')}}" value="{{Input::old('phone', null)}}">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">{{\Lang::get('/common/general.username')}}</label>
    <div class="col-sm-10">
        <input name="username" type="text" class="form-control" placeholder="{{Lang::get('common/general.username')}}" value="">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">{{\Lang::get('/common/general.password')}}</label>
    <div class="col-sm-10">
        <input name="password" type="password" class="form-control" placeholder="{{Lang::get('common/general.password')}}" value="">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">{{\Lang::get('/common/general.password_confirmation')}}</label>
    <div class="col-sm-10">
        <input name="password_confirmation" type="password" class="form-control" placeholder="{{Lang::get('common/general.password_confirmation')}}" value="">
    </div>
</div>

<div class="form-group">
    <div class="checkbox i-checks col-sm-offset-2 col-sm-4">
        <label>
            <input id="show-hide-pass" type="checkbox" >
            <i></i>
            Show/Hide Password
        </label>
    </div>
</div>