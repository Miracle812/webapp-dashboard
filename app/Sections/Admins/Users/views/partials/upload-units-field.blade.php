<?php $attr = ['class'=>'form-control'];?>
<?php $attr = $multiple ? ($attr + ['multiple']) : $attr;?>
<div class="form-group">
    <label class="col-sm-2 control-label">{{\Lang::get('/common/general.units')}}</label>
    <div class="col-sm-10">
        {{\Form::select('units[]', $units, $data, $attr)}}
    </div>
</div>