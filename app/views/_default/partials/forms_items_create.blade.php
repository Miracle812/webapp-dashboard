@if($items && count($items))
    @foreach($items as $item)
        <div class="form-group">
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
                case 'files_upload': $display = Form::fBFilesUploader($item); break;
                case 'yes_no':       $display = Form::fBYesno($item); break;
                case 'signature':    $display = Form::fBSignatureItem($item); break;
                case 'staff':        $display = Form::fBStaff($item); break;
                case 'assign_staff': $display = Form::fBAssignStaff($item); break;
                case 'compliant':    $display = Form::fBCompliant($item); break;
                case 'submit_button':$display = Form::fBSubmitButton($item); break;
                case 'file':         $display = Form::fBFile($item); break;
                case 'timepicker':   $display = Form::fBTimepicker($item); break;
                case 'datepicker':   $display = Form::fBDatepicker($item); break;
                case 'check-box':    $display = Form::fBCheckBox($item); break;
                case 'select':       $display = Form::fBSelect($item); break;
                case 'multiselect':  $display = Form::fBMultiselect($item); break;
                case 'checkbox':     $display = Form::fBCheckbox($item); break;
                case 'radio':        $display = Form::fBRadio($item); break;
                case 'input':        $display = Form::text('input['.$form->id.']['.$item->id.']', Input::old('item['.$form->id.']['.$item->id.']',  null),['class'=>'form-control', 'placeholder'=>$item->placeholder]); break;
                case 'textarea':     $display = Form::textarea('textarea['.$form->id.']['.$item->id.']', Input::old('item['.$form->id.']['.$item->id.']', null),['class'=>'form-control', 'placeholder'=>$item->placeholder,'rows'=>5]); break;
                default : $display = '';
            }
            ?>
            {{$display}}
        </div>
    @endforeach
@endif