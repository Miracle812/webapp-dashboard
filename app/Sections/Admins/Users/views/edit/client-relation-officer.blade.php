<div class="form-group">
    <label class="col-sm-2 control-label">{{\Lang::get('/common/general.sites')}}</label>
    <div class="col-sm-10" id="selectcombotree">
        <input name="units[]" class="easyui-combotree form-control" multiple data-options="url:'/units/tree-user-{{$user->id}}.json',method:'get',required:true,class:'fdgdfg'" >
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
        <input name="surname" type="text" class="form-control" placeholder="{{Lang::get('common/general.surname')}}" value="{{Input::old('surname',  $user->surname)}}">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">{{\Lang::get('/common/general.email')}}</label>
    <div class="col-sm-6">
        <input name="email" type="text" class="form-control" placeholder="{{Lang::get('common/general.email')}}" value="{{Input::old('email', $user->email)}}">
    </div>
    <div class="checkbox i-checks col-sm-4">
        <label>
            <input name="confirmed" type="checkbox" value="1" @if($user->confirmed) checked @endif>
            <i></i>
            {{\Lang::get('/common/general.confirmed')}}
        </label>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">{{\Lang::get('/common/general.mobile_phone')}}</label>
    <div class="col-sm-10">
        <input name="mobile_phone" type="text" class="form-control" placeholder="{{Lang::get('common/general.mobile_phone')}}" value="{{Input::old('mobile_phone', $user->mobile_phone)}}">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">{{\Lang::get('/common/general.phone')}}</label>
    <div class="col-sm-10 ">
        <input name="phone" type="text" class="form-control" placeholder="{{Lang::get('common/general.phone')}}" value="{{Input::old('phone', $user->phone)}}">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">{{\Lang::get('/common/general.username')}}</label>
    <div class="col-sm-10">
        <input name="username" type="text" class="form-control" placeholder="{{Lang::get('common/general.username')}}"  value="{{Input::old('phone', $user->username)}}">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">{{\Lang::get('/common/general.password')}}</label>
    <a href="{{URL::to('/users/edit/password/'.$user->id)}}" class="btn btn-primary col-sm-4 m-l" data-toggle="ajaxModal" type="submit"><i class="fa fa-key m-r"></i>{{Lang::get('common/general.password')}}</a>
</div>