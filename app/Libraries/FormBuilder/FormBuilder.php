<?php namespace Libraries\FormBuilder;

use \Illuminate\Html\FormBuilder as IlluminateFormBuilder;

class FormBuilder extends IlluminateFormBuilder
{
    public $formPackages = [];
    public function __construct(){

    }
    function itemsList ($items) {
        $html = '';
        if ($items && count($items)) {
            foreach ($items as $item) {
                switch ($item->type) {
                    case 'paragraph':       $display = $this->form_paragraph($item);break;
                    case 'timepicker':      $display = $this->form_timepicker($item);break;
                    case 'datepicker':      $display = $this->form_datepicker($item);break;
                    case 'datetimepicker':  $display = $this->form_datetimepicker($item);break;
                    case 'files_upload':    $display = $this->form_files_upload($item); $this->formPackages[$item->type] = true; break;
                    case 'yes_no':          $display = $this->form_yes_no($item);break;
                    case 'signature':       $display = $this->form_signature($item); $this->formPackages[$item->type] = true; break;
                    case 'staff':           $display = $this->form_staff($item);break;
                    case 'assign_staff':    $display = $this->form_assign_staff($item);break;
                    case 'compliant':       $display = $this->form_compliant($item);break;
                    case 'submit_button':   $display = $this->form_submit_button($item);break;
                    case 'file':            $display = $this->form_file($item);break;
                    //case 'check-box':       $display = $this->fb_checkbox($item);break;
                    case 'select':          $display = $this->form_select($item); break;
                    case 'multiselect':     $display = $this->form_multiselect($item);break;
                    case 'checkbox':        $display = $this->form_checkbox($item);break;
                    case 'radio':           $display = $this->form_radio($item);break;
                    case 'input':           $display = $this->form_input($item);break;
                    case 'textarea':        $display = $this->form_textarea($item);break;
                    default :$display = '';break;
                }
                $html .= $display;
            }
        }
        return $html;
    }

    function dispItems ($answer, $items) {
        $html = '';
        if ($items && count($items)) {
            foreach ($items as $item) {
                $value = \Model\FormsAnswersValues::whereAnswerId($answer->id)->whereItemLogId($item->id)->first();
                switch ($item->type) {
                    case 'paragraph':       $display = $this->disp_paragraph($answer,$item,$value);break;
                    case 'timepicker':      $display = $this->disp_timepicker($answer,$item,$value);break;
                    case 'datepicker':      $display = $this->disp_datepicker($answer,$item,$value);break;
                    case 'datetimepicker':  $display = $this->disp_datetimepicker($answer,$item,$value);break;
                    case 'files_upload':    $display = $this->disp_files_upload($answer,$item,$value); $this->formPackages[$item->type] = true; break;
                    case 'yes_no':          $display = $this->disp_yes_no($answer,$item,$value);break;
                    case 'signature':       $display = $this->disp_signature($answer,$item,$value); $this->formPackages[$item->type] = true; break;
                    case 'staff':           $display = $this->disp_staff($answer,$item,$value);break;
                    case 'assign_staff':    $display = $this->disp_assign_staff($answer,$item,$value);break;
                    case 'compliant':       $display = $this->disp_compliant($answer,$item,$value);break;
                    case 'submit_button':   $display = $this->disp_submit_button($answer,$item,$value);break;
                    case 'file':            $display = $this->disp_file($answer,$item,$value);break;
                    //case 'check-box':       $display = $this->fb_checkbox($item);break;
                    case 'select':          $display = $this->disp_select($answer,$item,$value); break;
                    case 'multiselect':     $display = $this->disp_multiselect($answer,$item,$value);break;
                    case 'checkbox':        $display = $this->disp_checkbox($answer,$item,$value);break;
                    case 'radio':           $display = $this->disp_radio($answer,$item,$value);break;
                    case 'input':           $display = $this->disp_input($answer,$item,$value);break;
                    case 'textarea':        $display = $this->disp_textarea($answer,$item,$value);break;
                    default :$display = '';break;
                }
                $html .= $display;
            }
        }
        return $html;
    }

    public function displayGenerator($answer)
    {
        $content = new \stdClass;
        $content -> html = $this->displayBuilder($answer);
        $content -> packages = $this->formPackages;
        return $content;
    }

    public function displayBuilder($answer)
    {
        $formLog = $answer -> form_log;
        $html = '';
        $groupedItems = $answer->groupedRootItems();
        if (count($groupedItems)) {
            foreach ($groupedItems as $rootItem) {
                if (isset($rootItem['item'])) {
                    $itemsLog = $rootItem['item'];
                    $html .= $this->dispItems($answer,$itemsLog);
                } else {
                    $html .= '<div class="md-card-content uk-row-first">';
                    $html .= '<ul data-uk-tab="{connect:\'#tabs_'.($rnm=rand(4,8)).'\', animation:\'slide-left\'}" class="uk-tab">';
                    foreach($rootItem['tabs'] as $tab){
                        $html .= '<li><a data-uk-tooltip="" title="'.($tab->description?:$tab->label).'" href="#">'.$tab->label.'</a></li>';
                    }
                    $html .= '</ul>';
                    $html .= '<ul class="uk-switcher uk-margin" id="tabs_'.$rnm.'">';
                    foreach($rootItem['tabs'] as $tab){
                        $itemsLog = \Model\FormsItemsLogs::whereFormLogId($formLog->id)->whereParentId($tab->org_id)->get();
                        $html .= '<li>'.$this->dispItems($answer,$itemsLog).'</li>';
                    }
                    $html .= '</ul>';
                    $html .= '</div>';
                }
            }
        }
        $updates = \Model\FormsAnswersUpdates::whereAnswerId($answer->id)->orderBy('id','DESC')->get();
        if($updates->count()){
            $html .= '<div class="uk-grid"><div class="uk-accordion uk-width-1-1" data-uk-accordion>';
                foreach($updates as $update){
                    $html .= '
                    <h3 class="uk-accordion-title">Update from '.$update->created_at().'</h3>
                    <div class="uk-accordion-content">
                        <p>'.$update->changes.'</p>
                        <p>Comment: '.$update->comment.'</p>
                        <p>Signature:</p>
                        <p class="uk-text-center">
                            <img src="'.$update->signature.'">
                            <div>Signed at: <span class="font-bold">'.$update->created_at().'</span></div>
                        </p>
                    </div>';
                }
            $html .= '</div></div>';
        }
        return $html;
    }



    public function addSectionFields($options)
    {
        $html = '';
        if(count($options)) {
            if ($form_id = (isset($options['form_id']) ? $options['form_id'] : null)) {
                $html .= '<input value="' . $form_id . '" name="form_base_id" type="hidden">';
            }
            if (($item_id = isset($options['item_id']) ? $options['item_id'] : null) && ($section = isset($options['section']) ? $options['section'] : null)) {
                $html .= '<input value="' . $item_id . '" name="' . $section . '[id]" type="hidden">';
            }
        }
        return $html;
    }

    public function formGenerator($form,$options=[])
    {
        $content = new \stdClass;
        $content -> html = $this->addSectionFields($options).$this->formBuilder($form);
        $content -> packages = $this->formPackages;
        return $content;
    }

    public function resolveBuilder($answer,$options=[])
    {
        return ($this->addSectionFields($options).$this->form_resolve_items($answer));
    }

    public function formBuilder($form)
    {
        $html = '';
        $groupedItems = $form->groupedRootItems();
        if (count($groupedItems)) {
            foreach ($groupedItems as $rootItem) {
                if (isset($rootItem['item'])) {
                    $items = $rootItem['item'];
                    $html .= $this->itemsList($items);
                } else {
                    $html .= '<div class="md-card-content uk-row-first">';
                        $html .= '<ul data-uk-tab="{connect:\'#formtabs_'.($rnm=rand(4,8)).'\', animation:\'slide-left\'}" class="uk-tab">';
                        foreach($rootItem['tabs'] as $tab){
                            $html .= '<li><a data-uk-tooltip="" title="'.($tab->description?:$tab->label).'" href="#">'.$tab->label.'</a></li>';
                        }
                        $html .= '</ul>';

                        $html .= '<ul class="uk-switcher uk-margin" id="formtabs_'.$rnm.'">';
                            foreach($rootItem['tabs'] as $tab){
                                $items = \Model\FormsItems::whereParentId($tab->id)->orderBy('sort','ASC')->get();
                                $html .= '<li>'.$this->itemsList($items).'</li>';
                            }
                        $html .= '</ul>';

                    $html .= '</div>';
                }
            }
        }
        return $html;
    }

    public function itemGrid($html,$item=null){
        return '<div class="uk-grid ">'.($item ? ('<h3 class="heading_a uk-margin-bottom navitas-text">'.($item->label ? : '').'<span class="sub-heading">'.($item->description ? : '').'</span></h3>') : '').'<div class="uk-width-1-1">'.$html.'</div></div>';
    }

    public function form_resolve_items($answer)
    {
        $html = '';
        $complaintsItems = $answer -> getComplaintsAnswers();
        foreach($complaintsItems as $value){
            $valueId = $value -> id;
            $item = $value -> itemLog;
            $options = unserialize($item->options);
            $value = ($answer && $value->value) ? unserialize($value->value) : [];
            if (isset($options['records'])) {
                foreach ($options['records'] as $key => $record) {
                    $name = 'yes_no[' . $answer->id . '][' . $valueId . '][' . $key . ']';
                    $active['yes'] = (\Input::old($name, $value[$key]) == 'yes') ? 'active' : '';
                    $active['no'] = (\Input::old($name, $value[$key]) == 'no') ? 'active' : '';
                    $checked['yes'] = (\Input::old($name, $value[$key]) == 'yes') ? 'checked' : '';
                    $checked['no'] = (\Input::old($name, $value[$key]) == 'no') ? 'checked' : '';
                    $buttons = (($options = unserialize($item->options))  && isset($options['buttons_colors'])) ? $options['buttons_colors'] : [];
                    $yes = isset($buttons['yes']) ? $buttons['yes'] : '#1aae88';
                    $no = isset($buttons['no']) ? $buttons['no'] : '#e33244';
                    $html .=
                        '<div class="uk-form-row">' .
                            '<h4 class="uk-margin-top navitas-text">' .$item->label.'</h4>'.
                            '<div class="uk-button-group" data-uk-button-radio>' .
                                '<label class="uk-button '.$active['no'].'" data-color="'.$no.'">'.
                                '<input type="radio" name="'.$name.'" '.$checked['no'].' value="no"/>'.
                                (\Lang::get('/common/general.no') ).'</label>'.
                                '<label class="uk-button '.$active['yes'].'" data-color='.$yes.'">'.
                                '<input type="radio" name="'.$name.'" '.$checked['yes'].' value="yes"/>'.
                                (\Lang::get('/common/general.yes')).'</label>'.
                            '</div>'.
                            '<label class="uk-margin-left">'.$record.'</label>'.
                        '</div>';
                }
            }
        }
        return $this->itemGrid($html);
    }

    public function form_radio($item){
        $html = '';
        $form = $item -> form;
        $options = unserialize($item->options);
        $arrVert = ($item->arrangement == 'vertical' ? true : false);
        if(isset($options['records'])){
            foreach($options['records'] as $key => $record){
                $name = 'radio['.$form->id.']['.$item->id.']';
                $html .= ($arrVert ? '<p>' : '<span class="icheck-inline">');
                $html .=
                    '<input '.(\Input::old($name, 1)===$key ? 'checked' : '').' type="radio" value="'.$key.'"  name="'.$name.'" id="'.$name.$key.'" data-md-icheck>'.
                    (\Form::label($name.$key,$record,['class'=>"inline-label"]));
                $html .= ($arrVert ? '</p>' : '</span>');
            }
        }
        return $this->itemGrid('<div class="md-input-wrapper">'.$html.'</div>',$item);
    }
    public function form_checkbox($item){
        $html = '';
        $form = $item -> form;
        $options = unserialize($item->options);
        $arrVert = ($item->arrangement == 'vertical' ? true : false);
        if(isset($options['records'])){
            foreach($options['records'] as $key => $record){
                $name = 'checkbox['.$form->id.']['.$item->id.']['.$key.']';
                $html .= ($arrVert ? '<p>' : '<span class="icheck-inline">');
                $html .=
                    '<input '.(\Input::old($name, 1)===$key ? 'checked' : '').' type="checkbox" value="'.$key.'"  name="'.$name.'" id="'.$name.$key.'" data-md-icheck>'.
                    (\Form::label($name.$key,$record,['class'=>"inline-label"]));
                $html .= ($arrVert ? '</p>' : '</span>');
            }
        }
        return $this->itemGrid('<div class="md-input-wrapper">'.$html.'</div>',$item);
    }
    public function form_select($item){
        $data = [];
        $form = $item -> form;
        $options = unserialize($item->options);
        $name = 'select['.$form->id.']['.$item->id.']';
        if(isset($options['records'])){
            foreach($options['records'] as $key => $record){
                $data[$key] = $record;
            }
        }
        return $this->itemGrid(
            '<div class="md-input-wrapper">'.
                (\Form::select($name, $data, (\Input::old($name, 1)), ['data-md-selectize'])).
            '</div>',$item
        );
    }
    public function form_multiselect($item){
        $data = [];
        $form = $item -> form;
        $options = unserialize($item->options);
        $name = 'multiselect['.$form->id.']['.$item->id.'][]';
        if(isset($options['records'])){
            foreach($options['records'] as $key => $record){
                $data[$key] = $record;
            }
        }
        return $this->itemGrid(
            '<div class="md-input-wrapper">'.
                (\Form::select($name, $data, (\Input::old($name, 1)),['data-md-selectize','multiple'=>'multiple'])).
            '</div>',$item
        );
    }
    public function form_input($item){
        $form = $item -> form;
        $name = 'input['.$form->id.']['.$item->id.']';
        return $this->itemGrid(
            '<div class="uk-form-row">'.
                (\Form::text($name, \Input::old($name,  null),['class'=>'md-input'])).
            '</div>',$item
        );
    }
    public function form_textarea($item){
        $form = $item -> form;
        $name = 'textarea['.$form->id.']['.$item->id.']';
        return $this->itemGrid(
            '<div class="uk-form-row">'.
                (\Form::textarea($name, \Input::old($name, null),['class'=>'md-input autosize_init','rows'=>5])).
            '</div>',$item
        );
    }
    public function form_timepicker($item){
        $form = $item -> form;
        $name = 'timepicker['.$form->id.']['.$item->id.']';
        return $this->itemGrid(
            '<div class="md-input-wrapper">'.
                (\Form::text($name, \Input::old($name, \Carbon::now()->format('H:i')),['data-uk-timepicker', 'class'=>'md-input'])).
            '</div>',$item
        );
    }
    public function form_datepicker($item){
        {
            $form = $item -> form;
            $name = 'datepicker['.$form->id.']['.$item->id.']';
            return $this->itemGrid(
                '<div class="md-input-wrapper">'.
                    (\Form::text($name, \Input::old($name, \Carbon::now()->format('d-m-Y')),['data-uk-datepicker'=>"{format:'DD.MM.YYYY'}",'class'=>'md-input'])).
                '</div>',$item
            );
        }
    }
    public function form_datetimepicker($item){
        $form = $item -> form;
        $name = 'datetimepicker['.$form->id.']['.$item->id.']';
        return $this->itemGrid(
            '<div class="md-input-wrapper">'.
                (\Form::text($name, \Input::old($name, \Carbon::now()->format('Y-m-d H:i')),['data-uk-datepicker','class'=>'md-input'])).
            '</div>',$item
        );
    }

    public function form_assign_staff($item){
        $user = \Auth::user();$data = [];
        if($user->hasRole('admin') || $user->hasRole('hq-manager'))
            return '<h4 class="text-danger">Not available form this panel.</h4>';
        $staffs = \Model\Staffs::whereUnitId($user->unit()->id)->get();

        foreach($staffs as $staff){
            $data[$staff->id] = $staff->fullname();
        }
        $html = '<div class="uk-form-row">'.
            (\Form::hidden('target_type', 'staffs')).
            (\Form::select('target_id', $data, \Input::old('target_id', null),['data-md-selectize'])).
            '</div>';
        return $this->itemGrid($html,$item);
    }

    public function form_staff($item){
        $user = \Auth::user();$data = [];
        if($user->hasRole('admin') || $user->hasRole('hq-manager'))
            return '<h4 class="text-danger">Not available form this panel.</h4>';
        $staffs = \Model\Staffs::whereUnitId($user->unit()->id)->get();

        foreach($staffs as $staff){
            $data[$staff->id] = $staff->fullname();
        }
        $form = $item -> form;
        $name = 'staff['.$form->id.']['.$item->id.']';
        $html = '<div class="uk-form-row">'.
                    (\Form::select($name, $data, \Input::old($name, null),['data-md-selectize'])).
                '</div>';
        return $this->itemGrid($html,$item);
    }
    public function form_signature($item){
        $form = $item -> form;
        $name = 'signature[' . $form->id . '][' . $item->id . ']';
        $html = '<div class="uk-form-row uk-text-center">
                    <div class="signature-pad-body">
                        <canvas id = "'.$name.'[sign]" class="uk-navbar" ></canvas>
                    </div>
                    '.(\Form::button(\Lang::get('/common/button.clear_sign'),['data-action'=>"clear_signature",'signId'=>$name.'[sign]','class'=>'md-btn md-btn-primary md-btn-wave-light waves-effect waves-button waves-light'])).'
                </div>';
        return $this->itemGrid($html,$item);
    }
    public function form_yes_no($item){
        $html = '';
        $form = $item -> form;
        $options = unserialize($item->options);
        if (isset($options['records'])) {
            foreach ($options['records'] as $key => $record) {
                $name = 'yes_no[' . $form->id . '][' . $item->id . '][' . $key . ']';
                $active['yes'] = (\Input::old($name, null) == 'yes') ? 'uk-active' : '';
                $active['no'] = (\Input::old($name, 'no') == 'no') ? 'uk-active' : '';
                $checked['yes'] = (\Input::old($name, null) == 'yes') ? 'checked' : '';
                $checked['no'] = (\Input::old($name, 'no') == 'no') ? 'checked' : '';
                $buttons = [];
                if (($options = unserialize($item->options)) && isset($options['buttons_colors'])):
                    $buttons = $options['buttons_colors'];
                endif;
                $yes = isset($buttons['yes']) ? $buttons['yes'] : '#1aae88';
                $no = isset($buttons['no']) ? $buttons['no'] : '#e33244';
                $html .=
                    '<div class="uk-form-row">' .
                        '<div class="uk-button-group" data-uk-button-radio>' .
                            '<label class="uk-button '.$active['no'].'" data-color="'.$no.'">'.
                                '<input type="radio" name="'.$name.'" '.$checked['no'].' value="no"/>'.
                            (\Lang::get('/common/general.no') ).'</label>'.
                            '<label class="uk-button '.$active['yes'].'" data-color='.$yes.'">'.
                                '<input type="radio" name="'.$name.'" '.$checked['yes'].' value="yes"/>'.
                            (\Lang::get('/common/general.yes')).'</label>'.
                        '</div>'.
                        '<span class="uk-margin-left">'.$record.'</span>'.
                    '</div>';
            }
        }
        return $this->itemGrid($html,$item);
    }
    public function form_submit_button($item){
        return $this->itemGrid(
            '<div class="uk-form-row uk-text-center">'.
                (\Form::button('Submit form',['class'=>'md-btn md-btn-success md-btn-wave-light waves-effect waves-button waves-light', 'type'=>'submit']))
            .'</div>'
        );
    }
    public function form_files_upload($item){
        $html = '';
        $form = $item -> form;
        $options = unserialize($item->options);
        if(isset($options['extensions']) && isset($options['file_size']))
        {
            $user = \Auth::user();
            $extensions = $options['extensions'];
            $fileSize   = $options['file_size'];
            $name = 'files_upload['.$form->id.']['.$item->id.']';
            $ident = $form->id.$item->id.$user->id;
            $html =
               '<div class="uk-margin" id="files_items_'.$ident.'" data-url="'.\URL::to("/form-processor/upload/$form->id/$item->id").'"></div>
                <div class="uk-form-row uk-text-center">
                    <input class="files_upload" id="queue_'.$ident.'" name="'.$name.'" ident="'.$ident.'" type="file" multiple="true" data-remote="'.\URL::to("/form-processor/upload/$form->id/$item->id").'">
                    <div class="'.($x1 ='uk-text-default text-xs').' m-t"><span class="font-bold">Allowed files:</span> '.implode(', ',$extensions).'</div>
                    <div class="'.$x1.'"><span class="font-bold">Allowed max file size:</span> '.$fileSize.' MB</div>
                </div>';
        }
        return $this->itemGrid($html,$item);
    }
    public function form_paragraph($item){
        $html = '';
        return $this->itemGrid($html,$item);
    }

    public function disp_files_upload($answer,$item,$value){
        $html = '';
        return $this->itemGrid($html,$item);
    }
    public function disp_timepicker($answer,$item,$value){
        $html = '<div class="text-primary font-bold">'.($value->value?:'N/A No data').'</div>';
        return $this->itemGrid($html,$item);
    }
    public function disp_datepicker($answer,$item,$value){
        $html = '<div class="text-primary font-bold">'.($value->value?:'N/A No data').'</div>';
        return $this->itemGrid($html,$item);
    }
    public function disp_datetimepicker($answer,$item,$value){
        $html = '<div class="text-primary font-bold">'.($value->value?:'N/A No data').'</div>';
        return $this->itemGrid($html,$item);
    }
    public function disp_yes_no($answer,$item,$value){
        $options = unserialize($item->options);
        $records = isset($options['records']) ? $options['records'] : [];
        $colours = isset($options['buttons_colors']) ? $options['buttons_colors'] : [];

        $value = ($value && $value->value) ? unserialize($value->value) : [];
        $html = '';
        foreach($records as $key => $name)
        {
            if(isset($value[$key]))
                if($value[$key] == 'yes')
                    $html .= '<span class="uk-badge uk-badge-success m-r" style="background-color:'.$colours['yes'].';"><i class="material-icons md-color-white">check</i> Yes</span>'.$name;
                else
                    $html .= '<span class="uk-badge uk-badge-danger m-r" style="background-color:'.$colours['no'].';"><i class="material-icons md-color-white">close</i> No</span>'.$name;
        }
        return $this->itemGrid($html,$item);
    }
    public function disp_signature($answer,$item,$value){
        $options = unserialize($value->value);
        $signature = (isset($options['signature'])&& !empty($options['signature']['sign'])) ? $options['signature'] : null;
        $html =  $signature ?
            '<div class="center" style="max-width:525px;margin:0 auto">
                <img width="100%" height="200" src="'.$signature['sign'].'"/></div>
                <div>signed at: <span class="font-bold">'.$value->created_at().'</span>
             </div>' :
            '<div class="text-primary font-bold">N/A, No Signature</div>';
        return $this->itemGrid($html,$item);
    }
    public function disp_staff($answer,$item,$value){
        $staff = \Model\Staffs::find((int)$value->value);
        $html = '<div class="text-primary font-bold">'.$staff->fullname().'</div>';
        return $this->itemGrid($html,$item);
    }
    public function disp_assign_staff($answer,$item,$value){
        $staff = \Model\Staffs::find((int)$answer->target_id);
        $html = '<div class="text-primary font-bold">'.$staff->fullname().'</div>';
        return $this->itemGrid($html,$item);
    }
    public function disp_compliant($answer,$item,$value){
        $title = 'Form data have been labeled as:';
        $options = unserialize($answer -> options);
        $compliant = isset($options['compliant']) ? $options['compliant'] : 'N/A';
        $target = $compliant == 'yes' ? '<span class="font-bold uk-text-success">Compliant</span>':'<span class="font-bold text-danger">Non compliant</span>';
        $html = '<h4 class="text-primary font-bold">'.$title. ' '.$target.'</h4>';
        return $this->itemGrid($html,$item);
    }
    public function disp_submit_button($answer,$item,$value){
        return $html = '';
        //return $this->itemGrid($html,$item);
    }
    public function disp_file($answer,$item,$value){
        $html = '';
        return $this->itemGrid($html,$item);
    }
    public function disp_select($answer,$item,$value){
        $options = unserialize($item->options);
        $records = isset($options['records']) ? $options['records'] : [];
        $answer = $value->value;
        $html = '';
        foreach($records as $key => $name)
        {
            if($key==(int)$answer)
                $html .= '<div><i class="material-icons uk-text-success m-r">check</i>'.$name.'</div>';
            else
                $html .= '<div><i class="fa fa-times uk-text-default m-r"></i>'.$name.'</div>';
        }
        return $this->itemGrid($html,$item);
    }
    public function disp_multiselect($answer,$item,$value){
        $options = unserialize($item->options);
        $records = isset($options['records']) ? $options['records'] : [];
        $answer = unserialize($value->value);
        $html = '';
        foreach($records as $key => $name)
        {
            if(in_array($key, $answer))
                $html .= '<div><i class="material-icons uk-text-success m-r">check</i>'.$name.'</div>';
            else
                $html .= '<div><i class="fa fa-times uk-text-default m-r"></i>'.$name.'</div>';
        }
        return $this->itemGrid($html,$item);
    }
    public function disp_checkbox($answer,$item,$value){
        $options = unserialize($item->options);
        $records = isset($options['records']) ? $options['records'] : [];
        $answer = unserialize($value->value);
        $html = '';
        foreach($records as $key => $name)
        {
            if(isset($answer[$key]))
                $html .= '<div><i class="material-icons uk-text-success m-r">check</i>'.$name.'</div>';
            else
                $html .= '<div><i class="fa fa-times uk-text-default m-r"></i>'.$name.'</div>';
        }
        return $this->itemGrid($html,$item);
    }
    public function disp_radio($answer,$item,$value){
        $options = unserialize($item->options);
        $records = isset($options['records']) ? $options['records'] : [];
        $answer = $value->value;
        $html = '';
        foreach($records as $key => $name)
        {
            if($key==(int)$answer)
                $html .= '<div><i class="material-icons uk-text-success m-r">check</i>'.$name.'</div>';
            else
                $html .= '<div><i class="fa fa-times uk-text-default m-r"></i>'.$name.'</div>';
        }
        return $this->itemGrid($html,$item);
    }
    public function disp_input($answer,$item,$value){
        $html = '<div class="text-primary font-bold">'.($value && $value->value?$value->value:'N/A No data').'</div>';
        return $this->itemGrid($html,$item);
    }
    public function disp_textarea($answer,$item,$value){
        $html = '<div class="text-primary font-bold">'.($value && $value->value?$value->value:'N/A No data').'</div>';
        return $this->itemGrid($html,$item);
    }
    public function disp_paragraph($answer,$item,$value){
        $html = '';
        return $this->itemGrid($html,$item);
    }

    public function common_files_uploader($options,$target)
    {
        if(isset($options['extensions']) && isset($options['file_size']))
        {
            $targetType = $target['target_type'];
            $targetId   = $target['target_id'];
            $extensions = $options['extensions'];
            $fileSize   = $options['file_size'];
            $name = 'files_upload['.$targetType.']['.$targetId.']';
            $ident = $targetType.'/'.$targetId;
            $view = 'newlayout.common.files_uploader';
            return \View::make($view)->with(['name'=>$name, 'ident'=>$ident,'extensions'=>$extensions,'fileSize'=>$fileSize]);
        }
        return '';
    }

    public function common_files_displayer($targetType, $targetId)
    {
        $files = \Model\Files::whereTargetType($targetType)->whereTargetId($targetId)->whereUserId(\Auth::user()->id)->get();
        if($files->count()) {
            $html = '';
            foreach ($files as $file) {
                $html .= \App::make('\Modules\FilesUploader')->getFileThumb($file, true);
            }
            return \View::make('newlayout.common.files_displayer')->with(['html' => $html])->render();
        }
        return '';
    }
}