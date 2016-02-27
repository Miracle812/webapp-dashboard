@foreach($itemsLog as $item)
    <?php $value = \Model\FormsAnswersValues::whereAnswerId($answer->id)->whereItemLogId($item->id)->first();?>
    <div class="form-group m-t">
        @if(!in_array($item->type,['submit_button']))
            @if($item->label)
            {{Form::label($item->type.'['.$form->id.']['.$item->id.']', $item->label,['class'=>'text-muted h4 m-b '.(($item->type == 'paragraph') ? 'font-bold':'') ])}}
            @endif
            @if($item->description)
                <span class="clear">{{$item->description}}</span>
            @endif
        @endif
        <?php
        switch ($item->type){
            case 'files_upload' :$display = HTML::ShowFilesUploader($answer,$item);break;
            case 'assign_staff' :$display = HTML::ShowAssignStaff($answer,$item); break;
            case 'compliant'    :$display = HTML::ShowCompliant($answer,$item); break;
            case 'paragraph'    :$display = HTML::ShowParagraph($item); break;
            case 'yes_no'       :$display = HTML::ShowYesno($value,$item); break;
            case 'signature'    :$display = HTML::ShowSignatureItem($value,$item); break;
            case 'staff'        :$display = HTML::ShowStaff($value,$item); break;
            case 'datepicker'   :$display = HTML::ShowDatepicker($value,$item); break;
            case 'timepicker'   :$display = HTML::ShowTimepicker($value,$item); break;
            case 'check-box'    :$display = HTML::ShowCheckBox($value,$item); break;
            case 'select'       :$display = HTML::ShowSelect($value,$item); break;
            case 'multiselect'  :$display = HTML::ShowMultiselect($value,$item); break;
            case 'checkbox'     :$display = HTML::ShowCheckbox($value,$item); break;
            case 'radio'        :$display = HTML::ShowRadio($value,$item); break;
            case 'input'        :$display = HTML::ShowText($value,$item); break;
            case 'textarea'     :$display = HTML::ShowTextarea($value,$item); break;
            default : $display = '';
        }
        ?>
    {{$display}}
    </div>
@endforeach