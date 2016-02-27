@if($items && count($items))
    @foreach($items as $item)
        <div class="uk-form-row">
            <?php if(!in_array($item->type,['submit_button'])): ?>
                <?php $sign = $item->type=='signature'?'[sign]' : '';?>
                @if($item->label)
                    {{Form::label($item->type.'['.$form->id.']['.$item->id.']'.$sign, $item->label,['class'=>'text-navitas h4 m-b '.(($item->type == 'paragraph') ? 'font-bold':'') ])}}
                @endif
                @if($item->description)
                    <span class="clear">{{$item->description}}</span>
                @endif
            <?php endif; ?>

            <?php
            switch ($item->type){
                case 'files_upload': $display = FormExt::form_files_upload($item); break;
                case 'yes_no':       $display = Form::mdFBYesno($item); break;
                case 'signature':    $display = Form::mdFBSignatureItem($item); break;
                case 'staff':        $display = Form::mdFBStaff($item); break;
                case 'assign_staff': $display = Form::mdFBAssignStaff($item); break;
                case 'compliant':    $display = Form::mdFBCompliant($item); break;
                case 'submit_button':$display = Form::mdFBSubmitButton($item); break;
                case 'file':         $display = Form::mdFBFile($item); break;
                case 'timepicker':   $display = Form::mdFBTimepicker($item); break;
                case 'datepicker':   $display = Form::mdFBDatepicker($item); break;
                case 'check-box':    $display = Form::mdFBCheckBox($item); break;
                case 'select':       $display = Form::mdFBSelect($item); break;
                case 'multiselect':  $display = Form::mdFBMultiselect($item); break;
                case 'checkbox':     $display = Form::mdFBCheckbox($item); break;
                case 'radio':        $display = Form::mdFBRadio($item); break;
                case 'input':        $display = '<label>'.$item->placeholder.'</label>'.Form::text('input['.$form->id.']['.$item->id.']', Input::old('item['.$form->id.']['.$item->id.']',  null),['class'=>'md-input']); break;
                case 'textarea':     $display = '<label>'.$item->placeholder.'</label>'.Form::textarea('textarea['.$form->id.']['.$item->id.']', Input::old('item['.$form->id.']['.$item->id.']', null),['class'=>'md-input','rows'=>5]); break;
                default : $display = '';
            }
            ?>
            {{$display}}
        </div>
    @endforeach
@endif