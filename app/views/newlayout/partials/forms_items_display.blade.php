@foreach($itemsLog as $item)
    <?php $value = \Model\FormsAnswersValues::whereAnswerId($answer->id)->whereItemLogId($item->id)->first();?>
    <div class="uk-form-row">
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
            case 'files_upload' :$display = HTML::mdShowFilesUploader($answer,$item);break;
            case 'assign_staff' :$display = HTML::mdShowAssignStaff($answer,$item); break;
            case 'compliant'    :$display = HTML::mdShowCompliant($answer,$item); break;
            case 'paragraph'    :$display = HTML::mdShowParagraph($item); break;
            case 'yes_no'       :$display = HTML::mdShowYesno($value,$item); break;
            case 'signature'    :$display = HTML::mdShowSignatureItem($value,$item); break;
            case 'staff'        :$display = HTML::mdShowStaff($value,$item); break;
            case 'datepicker'   :$display = HTML::mdShowDatepicker($value,$item); break;
            case 'timepicker'   :$display = HTML::mdShowTimepicker($value,$item); break;
            case 'check-box'    :$display = HTML::mdShowCheckBox($value,$item); break;
            case 'select'       :$display = HTML::mdShowSelect($value,$item); break;
            case 'multiselect'  :$display = HTML::mdShowMultiselect($value,$item); break;
            case 'checkbox'     :$display = HTML::mdShowCheckbox($value,$item); break;
            case 'radio'        :$display = HTML::mdShowRadio($value,$item); break;
            case 'input'        :$display = HTML::mdShowText($value,$item); break;
            case 'textarea'     :$display = HTML::mdShowTextarea($value,$item); break;
            default : $display = '';
        }
        ?>
    {{$display}}
    </div>
@endforeach