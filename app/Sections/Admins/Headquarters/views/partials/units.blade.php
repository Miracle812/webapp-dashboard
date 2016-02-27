<label class="col-sm-2 control-label">{{\Lang::get('/common/general.unit')}}</label>
<div class="col-sm-10 {{{ isset($units['error']) ? 'has-error' : '' }}}">
    {{\Form::select('unit[]', $units, null, ['class'=>'form-control'])}}
    @if(isset($units['error']))
        <div class="text-danger">{{ Lang::get('common/messages.not_exist')}}</div>
    @endif
</div>