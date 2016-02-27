<?php

function findinsetArray($array,$column)
{
    if(count($array)>1) {
        $findQuery = "(" .implode(" || ",
                array_map(function ($q) use ($column) {
                    return sprintf("FIND_IN_SET('%s', $column)", $q);
                }, $array)). ")";
    } elseif(count($array)==1){
        $findQuery = "(" ."FIND_IN_SET($array[0], $column)". ")";
    }
    return $findQuery;


    return implode(" AND ",array_map(
        function($v)use($column){
            return "(".implode(" || ",
                array_map( function($q)use($column){
                    return sprintf("FIND_IN_SET('%s', $column)",$q);
                }, [$v])).")";
        }, array_values($array)));
}

HTML::macro('widget', function($widgetName, $parameters = null)
{
    if (! is_array($parameters)) $parameters = (array) $parameters;
    $className = '\Widgets\\'.$widgetName;
    $widget = new $className();
    return  $widget->render($parameters);
});

Form::macro('editComplaints', function($answer)
{
    $nonCompliants = $answer -> getComplaintsAnswers();
    $form = $answer -> formLog;
    $html = '<small class="clear m-b text-danger">Outstanding tasks:</small>';
    foreach($nonCompliants as $value){
        $valueId = $value -> id;
        $item = $value -> itemLog;
        $options = unserialize($item->options);
        $value = ($answer && $value->value) ? unserialize($value->value) : [];
        if (isset($options['records'])) {
            foreach ($options['records'] as $key => $record) {
                if($value[$key] == 'no'){
                    $name = 'yes_no[' . $answer->id . '][' . $valueId . '][' . $key . ']';
                    $active['yes'] = (Input::old($name, $value[$key]) == 'yes') ? 'active' : '';
                    $active['no'] = (Input::old($name, $value[$key]) == 'no') ? 'active' : '';
                    $checked['yes'] = (Input::old($name, $value[$key]) == 'yes') ? 'checked' : '';
                    $checked['no'] = (Input::old($name, $value[$key]) == 'no') ? 'checked' : '';
                    $buttons = (($options = unserialize($item->options))  && isset($options['buttons_colors'])) ? $options['buttons_colors'] : [];
                    $yes = isset($buttons['yes']) ? $buttons['yes'] : '#1aae88';
                    $no = isset($buttons['no']) ? $buttons['no'] : '#e33244';
                    $html .= '<label class="font-bold">'.$item->label.'</label><div class="row m-b">' .
                        '<div class="padder pull-left">' .
                        '<div data-toggle="buttons" class="btn-group">' .
                        '<label class="btn btn-sm text-white ' . $active['yes'] . ' " style = "background-color: ' . $yes . '">' .
                        '<input type="radio" name="' . $name . '" ' . $checked['yes'] . ' value="yes"><i class="fa fa-check text-active"></i> ' . \Lang::get('/common/general.yes') .
                        '</label>' .
                        '<label class="btn btn-sm text-white ' . $active['no'] . ' " style = "background-color: ' . $no . '">' .
                        '<input type="radio" name="' . $name . '" ' . $checked['no'] . ' value="no"><i class="fa fa-check text-active"></i> ' . \Lang::get('/common/general.no') .
                        '</label>'.
                        '</div>'.
                        '</div>'.
                        '<div>'.
                        $record.
                        '</div>'.
                        '</div>';
                }
            }
        }
    }
    return $html;
});


HTML::macro('ownPopoverButton', function($content, $button, $title)
{
    return '<a
            style="position: relative"
            data-toggle="popover"
            data-html="true"
            data-placement="top"
            data-content="'.$content.'"
            title=""
            data-original-title="<button type=\'button\' class=\'close pull-right m-l\' data-dismiss=\'popover\'>&times;</button>'.$title.'">
                '.$button.'
        </a>';
});

HTML::macro('ownButton', function($id, $module, $action, $ico = 'fa-icon', $btn = 'btn-default')
{
    $destUrl = \URL::to('/'.$module.'/'.$action.'/'.$id);

    $dataHref = $ajaxToggle ='';
    if(strpos($action,'delete') !== false)
    {
        $href       = '/confirm-delete';
        $dataHref   = ' data-action = "'. $destUrl .'"';
        $ajaxToggle = ' data-toggle = "ajaxModal"';
    }
    else{
        $href       = $destUrl;
    }
    return '<a class="btn btn-rounded btn-sm btn-icon '.$btn.' inline" href="'.$href.'"'.$dataHref.$ajaxToggle.'><i class="fa '.$ico.'"></i></a>';
});

HTML::macro('ownModalButton', function($id, $module, $action, $ico = 'fa-icon', $btn = 'btn-default')
{
    return '<a class="btn btn-rounded btn-sm btn-icon '.$btn.' inline" href="'.\URL::to('/'.$module.'/'.$action.'/'.$id).'" data-toggle="ajaxModal" ><i class="fa '.$ico.'"></i></a>';
});

HTML::macro('ownIcoStatus', function($value = null)
{
    $class = $value ? 'success':'danger';
    $icon  = $value ? 'check':'times';
    return '<div class="btn-rounded btn-sm btn-icon btn-'.$class.' inline"><div class="hide">'.$value.'</div><i class="fa fa-'.$icon.'"></i></div>';
});

HTML::macro('ownNumStatus', function($value = null)
{
    $value = (int)$value;
    $class = $value ? 'success':'default';
    return '<div class="btn-rounded btn-sm btn-icon btn-'.$class.' inline">'.$value.'</div>';
});

HTML::macro('ownOuterBuilder', function($data, $tag = 'div', $class = 'text-center', $grouped = 'btn-group')
{
    return '<'.$tag.' class="'.$class.' '.$grouped.'">' . $data . '</'.$tag.'>';
});


HTML::macro('ownDropdown', function($links,$title,$btbClass)
{
    $html = '';
    foreach($links as $link) {
        foreach ($link as $url => $data) {
            if (is_string($data))
                $html .= $data;
            else
                $html .= '<li><a href="' . URL::to($url) . '" class="' . (isset($data[1]) ? $data[1] : '') . '" ' . (isset($data[2]) ? 'data-toggle="' . $data[2] . '"' : '') . '>' . $data[0] . '</a></li>';
        }
    }

    return
        '<div class="btn-group">
              <button class="btn btn-sm btn-rounded dropdown-toggle '.$btbClass.'" data-toggle="dropdown" aria-expanded="false">
                  <span class="dropdown-label">'.$title.'</span>
                  <span class="caret"></span>
              </button>
              <ul class="dropdown-menu">'.$html.'</ul>
         </div>';
});

//////////////////////////////////////////////////////////////////////////////////////////////

// New Skin Functions

HTML::macro('mdOwnPopoverButton', function($content, $button, $title)
{
    return '<a
            data-uk-tooltip="{cls:\'long-text\'}"
            title="'.$content.'">
                '.$button.'
        </a>';
});

HTML::macro('mdOwnButton', function($id, $module, $action, $ico = 'edit', $btn = 'md-btn-default', $title = '')
{
    $destUrl = \URL::to('/'.$module.'/'.$action.'/'.$id);
    $att_title = '';
    if ($title != '') {
        $att_title = ' data-uk-tooltip title="' . $title . '"';
    }
    $dataHref = $ajaxToggle ='';
    if(strpos($action,'delete') !== false)
    {
        $href       = 'javascript:;';
        $dataHref   = ' data-action = "'. $destUrl .'"';
        $ajaxToggle = ' data-modal = "ajaxConfirmDelete"';
    }
    else{
        $href       = $destUrl;
    }
    return '<a class="md-btn md-btn-wave-light waves-effect waves-button waves-light '.$btn.' inline" href="'.$href.'"'.$dataHref.$ajaxToggle.$att_title.'><i class="material-icons">'.$ico.'</i></a>';
});

HTML::macro('mdOwnModalButton', function($id, $module, $action, $ico = 'fa-icon', $btn = 'md-btn-default')
{
    return '<a class="md-btn md-btn-wave-light waves-effect waves-button waves-light '.$btn.' inline" href="'.\URL::to('/'.$module.'/'.$action.'/'.$id).'" data-toggle="ajaxModal" ><i class="fa '.$ico.'"></i></a>';
});

HTML::macro('mdOwnIcoStatus', function($value = null)
{
    $class = $value ? 'success':'danger';
    $icon  = $value ? 'check':'close';
    return '<span class="uk-badge uk-badge-'.$class.'"><i class="material-icons md-color-white">'.$icon.'</i></span>';
});

HTML::macro('mdOwnNumStatus', function($value = null)
{
    $value = (int)$value;
    $class = $value ? 'success':'default';
    return '<div class="uk-badge uk-badge-'.$class.' inline">'.$value.'</div>';
});


HTML::macro('mdOwnOuterBuilder', function($data, $tag = 'div', $class = 'uk-text-center', $grouped = '')
{
    return '<'.$tag.' class="'.$class.' '.$grouped.'">' . $data . '</'.$tag.'>';
});

HTML::macro('mdActionButton', function($id, $module, $action, $ico = 'edit', $title = '', $icon_class='')
{
    $destUrl = \URL::to('/'.$module.'/'.$action.'/'.$id);
    $att_title = '';
    if ($title != '') {
        $att_title = ' data-uk-tooltip title="' . $title . '"';
    }
    $dataHref = $ajaxToggle ='';
    if(strpos($action,'delete') !== false) {
        $href       = 'javascript:;';
        $dataHref   = ' data-action = "'. $destUrl .'"';
        $ajaxToggle = ' data-modal = "ajaxConfirmDelete"';
    }
    else{
        $href       = $destUrl;
        if ( strpos($action, 'active') !== false) {
           $ajaxToggle = ' data-toggle="ajaxActivate"';
        }
    }
    if ($icon_class != '') $icon_class = ' '.$icon_class;
    return '<a href="'.$href.'"'.$dataHref.$ajaxToggle.$att_title.'><i class="md-icon material-icons'.$icon_class.'">'.$ico.'</i></a>';
});


//////////////////////////////////////////////////////////////////////////////////////////////
//to check

HTML::macro('Notifications', function()
{
    $user = \Auth::user();
    $notifications = $user->getNotifications();
    return View::make('_panel.common.notifications.lists')->with(['notifications'=>$notifications])->render();
});

HTML::macro('Navichat', function()
{
    $messages = \Services\Messages::getGroupedMessages();
    return View::make('_default.partials.navichat.header-dropdown')->with(['messages'=>$messages])->render();
});

HTML::macro('NavichatGroupedMessages', function()
{
    $messages = \Services\Messages::getGroupedMessages();
    $html='';
    if($messages->count()) {
        foreach ($messages as $message) {
            $author = $message->author;
            $html .=
                '<div class="media list-group-item">
                <span class="pull-left thumb avatar">
                    <img class="img-circle" src="' . $author->avatar() . '">
                    <i class="' . ($author->isOnline() ? 'on' : 'off') . ' b-white bottom"></i>
                </span>
                <span class="pull-right">
                    <a class="btn btn-icon-xs tooltip-link" id="navichat-mark-read" data-placement="left" title="Mark as readed"  href="' . (URL::to('/messages-system/mark-read/' . $message->id)) . '"><i class="fa  fa-check-circle text-muted"></i></a>
                </span>
                <a href="' . URL::to('/messages-system/display/' . $message->id) . '" data-toggle="ajaxModal">
                    <span class="media-body block m-b-none">
                        <div>
                            <small class="text-muted font-bold">' . $author->fullname() . ', </small><small class="text-muted">' . $message->date() . '</small>
                        </div>
                        <span class="text-navitas">' . strip_tags(str_limit($message->message, 50, '...')) . '</span>
                    </span>
                </a>
            </div>';
        }
    }
    else{
        $html .= '<div class="media list-group-item m-b-none">
                      <span class="media-body block m-b-none text-muted font-bold">
                           No new messages.
                      </span>
                  </div>';
    }

    return $html;
});

HTML::macro('showNaviDialog', function($message,$images = true)
{
    $side   = $message->imAuthor() ? 'left' : 'right';
    $author = $message->author;
    return
        '<article class="chat-item '.$side.'">'.
        ($images ?'
                <a class="pull-'.$side.' thumb-sm avatar" href="#">
                    <img class="img-circle" src="'.$author->avatar().'">
                    <i class="'.($author->isOnline()?'on':'off').' b-white bottom"></i>
                </a>' : '').'
                <section class="chat-body">
                    <div class="panel b-light text-sm m-b-none">
                        <div class="panel-body">
                            <span class="arrow '.$side.'"></span>
                            <p class="m-b-none">'.$message->message.'</p>
                        </div>
                    </div>
                    <small class="text-muted"><i class="fa fa-ok text-success"></i>'.$message->created_at().', '.$author->fullname().' </small>
                </section>
            </article>';
});

HTML::macro('DatatableFilter', function($html = '')
{
    return View::make('_default.partials.datatable.filter')->with(['html'=>$html])->render();
});




























//to remove

/*
Form::macro('fBCheckList', function($item,$data = null)
{
    $form = $item -> form;
    return '<input type="awesome">';
});

Form::macro('fBRadio', function($item,$data = null)
{
    $html = '';
    $form = $item -> form;
    $options = unserialize($item->options);
    if(isset($options['records'])){
        $html .= '<div class="radio i-checks clear m-b">';
        foreach($options['records'] as $key => $record){
            $name = 'radio['.$form->id.']['.$item->id.']';
            $html .= '<label class=" '.($item->arrangement == 'vertical' ? 'col-sm-12' : 'm-r').'"><input '.(Input::old($name, 1)===$key ? 'checked' : '').' type="radio" value="'.$key.'"  name="'.$name.'"><i></i>'.$record.'</label>';
        }
        $html .= '</div>';
    }
    return $html;
});

Form::macro('fBCheckbox', function($item,$data = null)
{
    $html = '';
    $form = $item -> form;
    $options = unserialize($item->options);
    if(isset($options['records'])){
        $html .= '<div class="checkbox i-checks clear m-b">';
        foreach($options['records'] as $key => $record){
            $name = 'checkbox['.$form->id.']['.$item->id.']['.$key.']';
            $html .= '<label class=" '.($item->arrangement == 'vertical' ? 'col-sm-12' : 'm-r').'">
            <input '.(Input::old($name, null)===$key ? 'checked' : '').' type="checkbox" value="checked"  name="'.$name.'"><i></i>'.$record.'</label>';
        }
        $html .= '</div>';
    }
    return $html;
});

Form::macro('fBSelect', function($item,$data = null)
{
    $data = [];
    $form = $item -> form;
    $options = unserialize($item->options);
    $name = 'select['.$form->id.']['.$item->id.']';
    if(isset($options['records'])){
        foreach($options['records'] as $key => $record){
            $data[$key] = $record;
        }
    }
    return Form::select($name, $data, (Input::old($name, 1)),['class'=>'form-control']);
});
Form::macro('fBMultiselect', function($item,$data = null)
{
    $data = [];
    $form = $item -> form;
    $options = unserialize($item->options);
    $name = 'multiselect['.$form->id.']['.$item->id.'][]';
    if(isset($options['records'])){
        foreach($options['records'] as $key => $record){
            $data[$key] = $record;
        }
    }
    return Form::select($name, $data, (Input::old($name, 1)),['class'=>'form-control','multiple'=>'multiple']);
});

Form::macro('fBDatepicker', function($item,$data = null)
{
    $form = $item -> form;
    $name = 'datepicker['.$form->id.']['.$item->id.']';
    Basset::show('package_datetimepicker.css');
    return Form::text($name, Input::old($name, \Carbon::now()->format('Y-m-d')),['class'=>'form-control datetimepicker', 'placeholder'=>$item->placeholder]);
});

Form::macro('fBTimepicker', function($item,$data = null)
{
    $form = $item -> form;
    $name = 'timepicker['.$form->id.']['.$item->id.']';
    Basset::show('package_datetimepicker.css');
    return Form::text($name, Input::old($name, \Carbon::now()->format('H:i')),['class'=>'form-control timepicker', 'placeholder'=>$item->placeholder]);
});

Form::macro('fBStaff', function($item,$data = null)
{
    $user = \Auth::user();$data = [];
    if($user->hasRole('admin') || $user->hasRole('hq-manager'))
        return '<h4 class="text-danger">Not available form this panel.</h4>';
    $staffs = \Model\Staffs::whereUnitId($user->unit()->id)->get();

    foreach($staffs as $staff){
        $data[$staff->id] = $staff->fullname();
    }
    $html = '';
    if($item == 'target'){
        $html = Form::label('target_type', 'Assign form to:', ['class'=>'h4 text-danger m-b']);
        $html .= Form::hidden('target_type', 'staffs');
        $name = 'target_id';
    }
    else{
        $form = $item -> form;
        $name = 'staff['.$form->id.']['.$item->id.']';
    }
    $html .= Form::select($name, $data, Input::old($name, null),['class'=>'form-control']);
    return $html;
});

Form::macro('fBStaffDisplay', function($data){
    $staff = \Model\Staffs::find((int)$data->value);
    return $staff->fullname();
});

Form::macro('fBSignatureItem', function($item,$data = null)
{
    $form = $item -> form;
    $name = 'signature[' . $form->id . '][' . $item->id . ']';
    return
        '<div class="row">
            <div class="form-group">
                <div class="col-sm-12">
                    <div class="center" style="max-width:560px;margin:0 auto">
                        <div class="panel-body">
                            <div id="signature-pad" class="m-signature-pad">
                                <div class="m-signature-pad--body">
                                    <canvas id = "'.$name.'[sign]" ></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12 text-center m-t">
                    <button class="btn btn-default" data-action="clear" buttonId="'.$name.'[sign]">'.\Lang::get('/common/button.clear_sign').'</button>
                </div>
            </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>';
});

Form::macro('fBSubmitButton', function($item){
    return
        '<div class="col-sm-12 text-center m-t">'.
            \Form::submit($item->label,['class'=>'btn btn-success','data-action'=>'save']).
        '</div>';
});

Form::macro('fBAssignStaff', function($item,$data = null)
{
    $user = \Auth::user(); $data = [];
    if($user->hasRole('admin') || $user->hasRole('hq-manager'))
        return '<h4 class="text-danger">Not available form this panel.</h4>';
    $staffs = \Model\Staffs::whereUnitId($user->unit()->id)->get();
    foreach($staffs as $staff)
        $data[$staff->id] = $staff->fullname();
    return
        '<div class="row m-b padder">' .
            Form::hidden('target_type', 'staffs').
            Form::select('target_id', $data, Input::old('target_id', null),['class'=>'form-control']).
        '</div>';
});

Form::macro('fBCompliant', function($item){
    $options = unserialize($item->options);
    $question = isset($options['compliant_question']) ? $options['compliant_question'] : 'Is form data compliant?';
        return
            '<div class="row m-b">' .
                '<div class="padder pull-left">' .
                    '<div data-toggle="buttons" class="btn-group">' .
                        '<label class="btn btn-sm text-white active " style = "background-color: #1aae88">' .
                            '<input type="radio" name="compliant" checked value="yes"><i class="fa fa-check text-active"></i> ' . \Lang::get('/common/general.yes') .
                        '</label>' .
                        '<label class="btn btn-sm text-white " style = "background-color: #e33244">' .
                            '<input type="radio" name="compliant" value="no"><i class="fa fa-check text-active"></i> ' . \Lang::get('/common/general.no') .
                        '</label>' .
                    '</div>' .
                '</div>' .
                '<div>'.$question.'</div>' .
            '</div>';
});

Form::macro('fBYesno', function($item,$data = null)
{
    $html = '';
    $form = $item -> form;
    $options = unserialize($item->options);
    if (isset($options['records'])) {
        foreach ($options['records'] as $key => $record) {
            $name = 'yes_no[' . $form->id . '][' . $item->id . '][' . $key . ']';
            $active['yes'] = (Input::old($name, null) == 'yes') ? 'active' : '';
            $active['no'] = (Input::old($name, 'no') == 'no') ? 'active' : '';
            $checked['yes'] = (Input::old($name, null) == 'yes') ? 'checked' : '';
            $checked['no'] = (Input::old($name, 'no') == 'no') ? 'checked' : '';
            $buttons = [];
            if (($options = unserialize($item->options)) && isset($options['buttons_colors'])):
                $buttons = $options['buttons_colors'];
            endif;
            $yes = isset($buttons['yes']) ? $buttons['yes'] : '#1aae88';
            $no = isset($buttons['no']) ? $buttons['no'] : '#e33244';

            $html .= '<div class="row m-b">' .
                '<div class="padder pull-left">' .
                '<div data-toggle="buttons" class="btn-group">' .
                '<label class="btn btn-sm text-white ' . $active['yes'] . ' " style = "background-color: ' . $yes . '">' .
                '<input type="radio" name="' . $name . '" ' . $checked['yes'] . ' value="yes"><i class="fa fa-check text-active"></i> ' . \Lang::get('/common/general.yes') .
                '</label>' .
                '<label class="btn btn-sm text-white ' . $active['no'] . ' " style = "background-color: ' . $no . '">' .
                '<input type="radio" name="' . $name . '" ' . $checked['no'] . ' value="no"><i class="fa fa-check text-active"></i> ' . \Lang::get('/common/general.no') .
                '</label>'.
                '</div>'.
                '</div>'.
                '<div>'.
                $record.
                '</div>'.
                '</div>';
        }
    }
    return $html;
});

Form::macro('fBFilesUploader', function($item,$data = null)
{
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
        '<div class="row">
            <div class="col-sm-12">
                <div id="media" class="row form-group">
                    <div class="col-sm-4 m-b">
                        <input class="files_upload" id="queue_'.$ident.'" name="'.$name.'" ident="'.$ident.'" type="file" multiple="true" data-remote="'.\URL::to("/form-processor/upload/$form->id/$item->id").'">
                        <div class="text-default text-xs m-t"><span class="font-bold">Allowed files:</span> '.implode(', ',$extensions).'</div>
                        <div class="text-default text-xs"><span class="font-bold">Allowed max file size:</span> '.$fileSize.' MB</div>
                    </div>
                    <div class="col-sm-8">
                        <div id="files_items_'.$ident.'" data-url="'.\URL::to("/form-processor/upload/$form->id/$item->id").'"></div>
                    </div>
                </div>
            </div>
        </div>';
    }
    return $html;
});


HTML::macro('ShowFilesUploader', function($answer,$item)
{
    $files = \Model\FormsFiles::whereUnitId($answer->unit_id)->whereItemLogId($item->id)->whereAnswerId($answer->id)->get();
    $html = '';
    if($files->count()) {
        foreach ($files as $file) {
            $name = $file->file_name;
            $ext = pathinfo($name, PATHINFO_EXTENSION);

            $href = '#';
            $short = (strlen($name) > 20) ? substr($name, 0, 20) . '_' : $name;
            $images = false;
            switch ($ext) {
                case 'jpg':case 'jpeg':case 'png':case 'gif':case 'bmp':
                    $ico = 'fa-file-image-o';
                    $href = \URL::to($file->file_path . $name);
                    $images = true;
                    break;
                case 'doc':
                case 'docx':
                case 'odt':
                case 'rtf':
                    $ico = 'fa-file-word-o';
                    break;
                case 'xls':
                case 'xlsx':
                    $ico = 'fa-file-excel-o';
                    break;
                case 'txt':
                    $ico = 'fa-file-text-o';
                    break;
                case 'pdf':
                    $ico = 'fa-file-pdf-o';
                    break;
                default :
                    return '';
                    break;
            }
            $html .= '
                <div class="col-sm-2 m-b">
                    <a data-toggle="dropdown" class="btn btn-default tooltip-link" href="' . $href . '" title = "' . $short . '.' . $ext . '">
                        <i class="fa fa-5x ' . $ico . '"></i>
                    </a>
                    <ul class="dropdown-menu">'.
                        ($images ? '<li><a  class="form-file-display" href="'.\URL::to($href).'"><span  class="text-primary"><i class="fa fa-search m-r"></i></span>Display</a></a></li>':'').
                        '<li><a id="form-file-download" href="/form-processor/file/download/' . $file->id . '"><span  class="text-success"><i class="fa fa-download m-r"></i></span>Download</a></a></li>
                    </ul>
                </div>';
        }
    }
    else
    {
        $html = '<h4>No files</h4>';
    }
    return
    '<div class="row">
        <div class="col-sm-12 b-b">
            '.$html.'
        </div>
    </div>';
});
HTML::macro('ShowYesno', function($answer,$item){
    $options = unserialize($item->options);
    $records = isset($options['records']) ? $options['records'] : [];
    $colours = isset($options['buttons_colors']) ? $options['buttons_colors'] : [];

    $value = ($answer && $answer->value) ? unserialize($answer->value) : [];
    $html = '';
    foreach($records as $key => $name)
    {
        if(isset($value[$key]))
            if($value[$key] == 'yes')
                $html .= '<div class="row padder m-b"><div class="btn btn-sm text-white m-r" style="background-color:'.$colours['yes'].';"><i class="fa fa-check m-r" ></i>Yes</div>'.$name.'</div>';
            else
                $html .= '<div class="row padder m-b"><div class="btn btn-sm text-white m-r" style="background-color:'.$colours['no'].';"><i class="fa fa-times m-r" ></i>No</div>'.$name.'</div>';
    }
    return
        '<div class="row">
        <div class="col-sm-12 b-b">
            '.$html.'
        </div>
    </div>';
});
HTML::macro('ShowStaff', function($answer,$item){

    $staff = \Model\Staffs::find((int)$answer->value);
    return $staff ?
    '<div class="row">
        <div class="col-sm-12 b-b">
            <div class="text-primary font-bold">'.$staff->fullname().'</div>
        </div>
    </div>' : '';
});

HTML::macro('ShowAssignStaff', function($answer){
    $staff = \Model\Staffs::find((int)$answer->target_id);
    return $staff ?
        '<div class="row">
        <div class="col-sm-12 b-b">
            <div class="text-primary font-bold">'.$staff->fullname().'</div>
        </div>
    </div>' : '';
});

HTML::macro('ShowDatepicker', function($answer,$item){
    return
    '<div class="row">
        <div class="col-sm-12 b-b">
            <div class="text-primary font-bold">'.($answer->value?:'N/A No data').'</div>
        </div>
    </div>';
});

HTML::macro('ShowTimepicker', function($answer,$item){
    return
        '<div class="row">
        <div class="col-sm-12 b-b">
            <div class="text-primary font-bold">'.($answer->value?:'N/A No data').'</div>
        </div>
    </div>';
});

HTML::macro('ShowCheckBox', function($answer,$item){
    $options = unserialize($item->options);
    $records = isset($options['records']) ? $options['records'] : [];
    $answer = unserialize($answer->value);

    $html = '';
    foreach($records as $key => $name)
    {
        if(in_array($key, $answer))
            $html .= '<div><i class="fa fa-check text-success m-r"></i>'.$name.'</div>';
        else
            $html .= '<div><i class="fa fa-times text-default m-r"></i>'.$name.'</div>';
    }
    return
        '<div class="row">
        <div class="col-sm-12 b-b">
            '.$html.'
        </div>
    </div>';
});
HTML::macro('ShowSelect', function($answer,$item){
    $options = unserialize($item->options);
    $records = isset($options['records']) ? $options['records'] : [];
    $answer = $answer->value;
    $html = '';
    foreach($records as $key => $name)
    {
        if($key==(int)$answer)
            $html .= '<div><i class="fa fa-check text-success m-r"></i>'.$name.'</div>';
        else
            $html .= '<div><i class="fa fa-times text-default m-r"></i>'.$name.'</div>';
    }
    return
        '<div class="row">
        <div class="col-sm-12 b-b">
            '.$html.'
        </div>
    </div>';
});
HTML::macro('ShowMultiselect', function($answer,$item){
    $options = unserialize($item->options);
    $records = isset($options['records']) ? $options['records'] : [];
    $answer = unserialize($answer->value);

    $html = '';
    foreach($records as $key => $name)
    {
        if(in_array($key, $answer))
            $html .= '<div><i class="fa fa-check text-success m-r"></i>'.$name.'</div>';
        else
            $html .= '<div><i class="fa fa-times text-default m-r"></i>'.$name.'</div>';
    }
    return
        '<div class="row">
        <div class="col-sm-12 b-b">
            '.$html.'
        </div>
    </div>';
});
HTML::macro('ShowCheckbox', function($answer,$item){

    $options = unserialize($item->options);
    $records = isset($options['records']) ? $options['records'] : [];
    $answer = unserialize($answer->value);
    $html = '';

    foreach($records as $key => $name)
    {
        if(isset($answer[$key]))
            $html .= '<div><i class="fa fa-check text-success m-r"></i>'.$name.'</div>';
        else
            $html .= '<div><i class="fa fa-times text-default m-r"></i>'.$name.'</div>';
    }
    return
    '<div class="row">
        <div class="col-sm-12 b-b">
            '.$html.'
        </div>
    </div>';
});
HTML::macro('ShowRadio', function($answer,$item){
    $options = unserialize($item->options);
    $records = isset($options['records']) ? $options['records'] : [];
    $answer = $answer->value;
    $html = '';
    foreach($records as $key => $name)
    {
        if($key==(int)$answer)
            $html .= '<div><i class="fa fa-check text-success m-r"></i>'.$name.'</div>';
        else
            $html .= '<div><i class="fa fa-times text-default m-r"></i>'.$name.'</div>';
    }
    return
        '<div class="row">
        <div class="col-sm-12 b-b">
            '.$html.'
        </div>
    </div>';
});
HTML::macro('ShowText', function($answer,$item){
    return
        '<div class="row">
        <div class="col-sm-12 b-b">
            <div class="text-primary font-bold">'.($answer && $answer->value?$answer->value:'N/A No data').'</div>
        </div>
    </div>';
});
HTML::macro('ShowTextarea', function($answer,$item){
    return
    '<div class="row">
        <div class="col-sm-12 b-b">
            <div class="text-primary font-bold">'.($answer && $answer->value?$answer->value:'N/A No data').'</div>
        </div>
    </div>';
});
HTML::macro('ShowParagraph', function($item){
    return
    '<div class="row">
        <div class="col-sm-12 b-b">
        </div>
    </div>';
});
HTML::macro('ShowSignature', function($form){
    return $form -> signature ?
    '<div class="row">
        <div class="col-sm-12 b-b text-center">
            <div class="center" style="max-width:525px;margin:0 auto">
            <img width="100%" height="200" src="'.$form->signature.'"/></div>
            <div>created at: <span class="font-bold">'.$form->created_at().'</span></div>
        </div>
    </div>' : '';
});
HTML::macro('ShowSignatureItem', function($answer,$item){

    $options = unserialize($answer->value);
    $signature = (isset($options['signature'])&& !empty($options['signature']['sign'])) ? $options['signature'] : null;
    return $signature ?
        '<div class="row">
        <div class="col-sm-12 b-b text-center">
             <div class="center" style="max-width:525px;margin:0 auto">
            <img width="100%" height="200" src="'.$signature['sign'].'"/></div>
            <div>created at: <span class="font-bold">'.$answer->created_at().'</span></div>
        </div>
    </div>' : '<div class="text-primary font-bold">N/A, No Signature</div>';
});

HTML::macro('ShowCompliant', function($answer,$item)
{
    $title = 'Form data have been labeled as:';
    $options = unserialize($answer -> options);
    $compliant = isset($options['compliant']) ? $options['compliant'] : 'N/A';
    $target = $compliant == 'yes' ? '<span class="font-bold text-success">Compliant</span>':'<span class="font-bold text-danger">Non compliant</span>';
    return $target ?
        '<div class="row">
            <div class="col-sm-12 b-b">
                <h4 class="text-primary font-bold">'.$title. ' '.$target.'</h4>
            </div>
        </div>' : '';
});


Form::macro('FilesUploader', function($options,$target)
{
    if(isset($options['extensions']) && isset($options['file_size']))
    {
        $targetType = $target['target_type'];
        $targetId   = $target['target_id'];
        $extensions = $options['extensions'];
        $fileSize   = $options['file_size'];
        $name = 'files_upload['.$targetType.']['.$targetId.']';
        $ident = $targetType.'/'.$targetId;
        //$view = '_panel.common.files_uploader'.((isset($options['icons']) && ($options['icons'] == 'small')) ? '_small' : '');
        $view = '_panel.common.files_uploader';
        return View::make($view)->with(['name'=>$name, 'ident'=>$ident,'extensions'=>$extensions,'fileSize'=>$fileSize])->render();
    }
});

HTML::macro('FilesUploader', function($targetType, $targetId)
{
    $files = \Model\Files::whereTargetType($targetType)->whereTargetId($targetId)->whereUserId(\Auth::user()->id)->get();
    return View::make('_panel.common.files_displayer')->with(['files'=>$files])->render();
});




Form::macro('mdFBCheckList', function($item,$data = null)
{
    $form = $item -> form;
    return '<input type="awesome">';
});

Form::macro('mdFBRadio', function($item,$data = null)
{
    $html = '';
    $form = $item -> form;
    $options = unserialize($item->options);
    if(isset($options['records'])){
        $html .= '<div class="radio i-checks clear m-b">';
        foreach($options['records'] as $key => $record){
            $name = 'radio['.$form->id.']['.$item->id.']';
            $html .= '<label class=" '.($item->arrangement == 'vertical' ? 'col-sm-12' : 'm-r').'"><input '.(Input::old($name, 1)===$key ? 'checked' : '').' type="radio" value="'.$key.'"  name="'.$name.'" data-md-icheck><i></i>'.$record.'</label>';
        }
        $html .= '</div>';
    }
    return $html;
});

Form::macro('mdFBCheckbox', function($item,$data = null)
{
    $html = '';
    $form = $item -> form;
    $options = unserialize($item->options);
    if(isset($options['records'])){
        $html .= '<div class="checkbox i-checks clear m-b">';
        foreach($options['records'] as $key => $record){
            $name = 'checkbox['.$form->id.']['.$item->id.']['.$key.']';
            $html .= '<label class=" '.($item->arrangement == 'vertical' ? 'col-sm-12' : 'm-r').'">
            <input '.(Input::old($name, null)===$key ? 'checked' : '').' type="checkbox" value="checked"  name="'.$name.'" data-md-icheck><i></i>'.$record.'</label>';
        }
        $html .= '</div>';
    }
    return $html;
});

Form::macro('mdFBSelect', function($item,$data = null)
{
    $data = [];
    $form = $item -> form;
    $options = unserialize($item->options);
    $name = 'select['.$form->id.']['.$item->id.']';
    if(isset($options['records'])){
        foreach($options['records'] as $key => $record){
            $data[$key] = $record;
        }
    }
    return Form::select($name, $data, (Input::old($name, 1)),['data-md-selectize'=>'']);
});
Form::macro('mdFBMultiselect', function($item,$data = null)
{
    $data = [];
    $form = $item -> form;
    $options = unserialize($item->options);
    $name = 'multiselect['.$form->id.']['.$item->id.'][]';
    if(isset($options['records'])){
        foreach($options['records'] as $key => $record){
            $data[$key] = $record;
        }
    }
    return Form::select($name, $data, (Input::old($name, 1)),['data-md-selectize'=>'','multiple'=>'multiple']);
});

Form::macro('mdFBDatepicker', function($item,$data = null)
{
    $form = $item -> form;
    $name = 'datepicker['.$form->id.']['.$item->id.']';
    //Basset::show('package_datetimepicker.css');
    return Form::text($name, Input::old($name, \Carbon::now()->format('Y-m-d')),['class'=>'datetimepicker', 'data-format'=>'yyyy-MM-dd', 'style'=>'width:100%']);
});

Form::macro('mdFBTimepicker', function($item,$data = null)
{
    $form = $item -> form;
    $name = 'timepicker['.$form->id.']['.$item->id.']';
    //Basset::show('package_datetimepicker.css');
    return Form::text($name, Input::old($name, \Carbon::now()->format('H:i')),['class'=>'datetimepicker', 'data-format'=>'hh:mm', 'style'=>'width:100%']);
});

Form::macro('mdFBStaff', function($item,$data = null)
{
    $user = \Auth::user();$data = [];
    if($user->hasRole('admin') || $user->hasRole('hq-manager'))
        return '<h4 class="uk-text-danger">Not available form this panel.</h4>';
    $staffs = \Model\Staffs::whereUnitId($user->unit()->id)->get();

    foreach($staffs as $staff){
        $data[$staff->id] = $staff->fullname();
    }
    $html = '';
    if($item == 'target'){
        $html = Form::label('target_type', 'Assign form to:', ['class'=>'h4 uk-text-danger m-b']);
        $html .= Form::hidden('target_type', 'staffs');
        $name = 'target_id';
    }
    else{
        $form = $item -> form;
        $name = 'staff['.$form->id.']['.$item->id.']';
    }
    $html .= Form::select($name, $data, Input::old($name, null),['data-md-selectize'=>'']);
    return $html;
});

Form::macro('mdFBStaffDisplay', function($data){
    $staff = \Model\Staffs::find((int)$data->value);
    return $staff->fullname();
});

//incomplete
Form::macro('mdFBSignatureItem', function($item,$data = null)
{
    $form = $item -> form;
    $name = 'signature[' . $form->id . '][' . $item->id . ']';
    return
        '<div class="uk-form-row">
            <div class="uk-grid">
                <div class="uk-width-1-1">
                    <div class="center" style="max-width:560px;margin:0 auto">
                        <div id="signature-pad" class="m-signature-pad">
                            <div class="m-signature-pad--body">
                                <canvas id = "'.$name.'[sign]" ></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="uk-grid">
                <div class="uk-width-1-1 uk-text-center m-t">
                    <button class="md-btn md-btn-success" data-action="clear" buttonId="'.$name.'[sign]">'.\Lang::get('/common/button.clear_sign').'</button>
                </div>
            </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>';
});

Form::macro('mdFBSubmitButton', function($item){
    return
        '<div class="uk-form-row uk-text-right">'.
            \Form::submit($item->label,['class'=>'md-btn md-btn-success','data-action'=>'save']).
        '</div>';
});

Form::macro('mdFBAssignStaff', function($item,$data = null)
{
    $user = \Auth::user(); $data = [];
    if($user->hasRole('admin') || $user->hasRole('hq-manager'))
        return '<h4 class="text-danger">Not available form this panel.</h4>';
    $staffs = \Model\Staffs::whereUnitId($user->unit()->id)->get();
    foreach($staffs as $staff)
        $data[$staff->id] = $staff->fullname();
    return
        '<div class="uk-form-row">' .
            Form::hidden('target_type', 'staffs').
            Form::select('target_id', $data, Input::old('target_id', null),['data-md-selectize'=>'']).
        '</div>';
});

//incomplete
Form::macro('mdFBCompliant', function($item){
    $options = unserialize($item->options);
    $question = isset($options['compliant_question']) ? $options['compliant_question'] : 'Is form data compliant?';
        return
            '<div class="uk-form-row">' .
                '<div class="padder pull-left">' .
                    '<div data-toggle="buttons" class="btn-group">' .
                        '<label class="btn btn-sm text-white active " style = "background-color: #1aae88">' .
                            '<input type="radio" name="compliant" checked value="yes"><i class="fa fa-check text-active"></i> ' . \Lang::get('/common/general.yes') .
                        '</label>' .
                        '<label class="btn btn-sm text-white " style = "background-color: #e33244">' .
                            '<input type="radio" name="compliant" value="no"><i class="fa fa-check text-active"></i> ' . \Lang::get('/common/general.no') .
                        '</label>' .
                    '</div>' .
                '</div>' .
                '<div>'.$question.'</div>' .
            '</div>';
});

Form::macro('mdFBYesno', function($item,$data = null)
{
    $html = '';
    $form = $item -> form;
    $options = unserialize($item->options);
    if (isset($options['records'])) {
        foreach ($options['records'] as $key => $record) {
            $name = 'yes_no[' . $form->id . '][' . $item->id . '][' . $key . ']';
            $active['yes'] = (Input::old($name, null) == 'yes') ? 'active' : '';
            $active['no'] = (Input::old($name, 'no') == 'no') ? 'active' : '';
            $checked['yes'] = (Input::old($name, null) == 'yes') ? 'checked' : '';
            $checked['no'] = (Input::old($name, 'no') == 'no') ? 'checked' : '';
            $buttons = [];
            if (($options = unserialize($item->options)) && isset($options['buttons_colors'])):
                $buttons = $options['buttons_colors'];
            endif;
            $yes = isset($buttons['yes']) ? $buttons['yes'] : '#1aae88';
            $no = isset($buttons['no']) ? $buttons['no'] : '#e33244';
            $html .= '<div class="uk-form-row">
                            <input type="checkbox" data-switchery name="'.$name.'" '.$checked['yes'].' value="yes" />
                            <label class="inline-label">'.$record.'</label>
                        </div>';
        }
    }
    return $html;
});

HTML::macro('mdFilesUploader', function($targetType, $targetId)
{
    $files = \Model\Files::whereTargetType($targetType)->whereTargetId($targetId)->whereUserId(\Auth::user()->id)->get();
    return View::make('newlayout.common.files_displayer')->with(['files'=>$files])->render();
});

HTML::macro('mdInbox', function()
{
    $messages = \Services\Messages::getGroupedMessages();
    $user = \Auth::user();
    $notifications = $user->getNotifications();
    return View::make('newlayout.common.inbox.header-dropdown')->with(['notifications'=>$notifications, 'messages' => $messages])->render();
});



// New Skin Functions


HTML::macro('mdShowFilesUploader', function($answer,$item)
{
    $files = \Model\FormsFiles::whereUnitId($answer->unit_id)->whereItemLogId($item->id)->whereAnswerId($answer->id)->get();
    $html = '';
    if($files->count()) {
        foreach ($files as $file) {
            $name = $file->file_name;
            $ext = pathinfo($name, PATHINFO_EXTENSION);

            $href = '#';
            $short = (strlen($name) > 20) ? substr($name, 0, 20) . '_' : $name;
            $images = false;
            switch ($ext) {
                case 'jpg':case 'jpeg':case 'png':case 'gif':case 'bmp':
                    $ico = 'fa-file-image-o';
                    $href = \URL::to($file->file_path . $name);
                    $images = true;
                    break;
                case 'doc':
                case 'docx':
                case 'odt':
                case 'rtf':
                    $ico = 'fa-file-word-o';
                    break;
                case 'xls':
                case 'xlsx':
                    $ico = 'fa-file-excel-o';
                    break;
                case 'txt':
                    $ico = 'fa-file-text-o';
                    break;
                case 'pdf':
                    $ico = 'fa-file-pdf-o';
                    break;
                default :
                    return '';
                    break;
            }
            $html .= '
                <div class="col-sm-2 m-b">
                    <a data-toggle="dropdown" class="btn btn-default tooltip-link" href="' . $href . '" title = "' . $short . '.' . $ext . '">
                        <i class="fa fa-5x ' . $ico . '"></i>
                    </a>
                    <ul class="dropdown-menu">'.
                        ($images ? '<li><a  class="form-file-display" href="'.\URL::to($href).'"><span  class="text-primary"><i class="fa fa-search m-r"></i></span>Display</a></a></li>':'').
                        '<li><a id="form-file-download" href="/form-processor/file/download/' . $file->id . '"><span  class="uk-text-success"><i class="fa fa-download m-r"></i></span>Download</a></a></li>
                    </ul>
                </div>';
        }
    }
    else
    {
        $html = '<h4>No files</h4>';
    }
    return
    '<div class="uk-grid">
        <div class="uk-width-1-1">
            '.$html.'
        </div>
    </div>';
});

HTML::macro('mdShowYesno', function($answer,$item){
    $options = unserialize($item->options);
    $records = isset($options['records']) ? $options['records'] : [];
    $colours = isset($options['buttons_colors']) ? $options['buttons_colors'] : [];

    $value = ($answer && $answer->value) ? unserialize($answer->value) : [];
    $html = '';
    foreach($records as $key => $name)
    {
        if(isset($value[$key]))
            if($value[$key] == 'yes')
                $html .= '<span class="uk-badge uk-badge-success m-r" style="background-color:'.$colours['yes'].';"><i class="material-icons md-color-white">check</i> Yes</span>'.$name;
            else
                $html .= '<span class="uk-badge uk-badge-danger m-r" style="background-color:'.$colours['no'].';"><i class="material-icons md-color-white">close</i> No</span>'.$name;
    }
    return
        '<div class="uk-grid">
        <div class="uk-width-1-1">
            '.$html.'
        </div>
    </div>';
});

HTML::macro('mdShowStaff', function($answer,$item){

    $staff = \Model\Staffs::find((int)$answer->value);
    return $staff ?
    '<div class="uk-grid">
        <div class="uk-width-1-1">
            <div class="text-primary font-bold">'.$staff->fullname().'</div>
        </div>
    </div>' : '';
});

HTML::macro('mdShowAssignStaff', function($answer){
    $staff = \Model\Staffs::find((int)$answer->target_id);
    return $staff ?
        '<div class="uk-grid">
        <div class="uk-width-1-1">
            <div class="text-primary font-bold">'.$staff->fullname().'</div>
        </div>
    </div>' : '';
});

HTML::macro('mdShowDatepicker', function($answer,$item){
    return
    '<div class="uk-grid">
        <div class="uk-width-1-1">
            <div class="text-primary font-bold">'.($answer->value?:'N/A No data').'</div>
        </div>
    </div>';
});

HTML::macro('mdShowTimepicker', function($answer,$item){
    return
        '<div class="uk-grid">
        <div class="uk-width-1-1">
            <div class="text-primary font-bold">'.($answer->value?:'N/A No data').'</div>
        </div>
    </div>';
});

HTML::macro('mdShowCheckBox', function($answer,$item){
    $options = unserialize($item->options);
    $records = isset($options['records']) ? $options['records'] : [];
    $answer = unserialize($answer->value);

    $html = '';
    foreach($records as $key => $name)
    {
        if(in_array($key, $answer))
            $html .= '<div><i class="material-icons uk-text-success m-r">check</i>'.$name.'</div>';
        else
            $html .= '<div><i class="material-icons uk-text-default m-r">close</i>'.$name.'</div>';
    }
    return
        '<div class="uk-grid">
        <div class="uk-width-1-1">
            '.$html.'
        </div>
    </div>';
});

HTML::macro('mdShowSelect', function($answer,$item){
    $options = unserialize($item->options);
    $records = isset($options['records']) ? $options['records'] : [];
    $answer = $answer->value;
    $html = '';
    foreach($records as $key => $name)
    {
        if($key==(int)$answer)
            $html .= '<div><i class="material-icons uk-text-success m-r">check</i>'.$name.'</div>';
        else
            $html .= '<div><i class="fa fa-times uk-text-default m-r"></i>'.$name.'</div>';
    }
    return
        '<div class="uk-grid">
        <div class="uk-width-1-1">
            '.$html.'
        </div>
    </div>';
});

HTML::macro('mdShowMultiselect', function($answer,$item){
    $options = unserialize($item->options);
    $records = isset($options['records']) ? $options['records'] : [];
    $answer = unserialize($answer->value);

    $html = '';
    foreach($records as $key => $name)
    {
        if(in_array($key, $answer))
            $html .= '<div><i class="material-icons uk-text-success m-r">check</i>'.$name.'</div>';
        else
            $html .= '<div><i class="fa fa-times uk-text-default m-r"></i>'.$name.'</div>';
    }
    return
        '<div class="uk-grid">
        <div class="uk-width-1-1">
            '.$html.'
        </div>
    </div>';
});

HTML::macro('mdShowCheckbox', function($answer,$item){

    $options = unserialize($item->options);
    $records = isset($options['records']) ? $options['records'] : [];
    $answer = unserialize($answer->value);
    $html = '';

    foreach($records as $key => $name)
    {
        if(isset($answer[$key]))
            $html .= '<div><i class="material-icons uk-text-success m-r">check</i>'.$name.'</div>';
        else
            $html .= '<div><i class="fa fa-times uk-text-default m-r"></i>'.$name.'</div>';
    }
    return
    '<div class="uk-grid">
        <div class="uk-width-1-1">
            '.$html.'
        </div>
    </div>';
});

HTML::macro('mdShowRadio', function($answer,$item){
    $options = unserialize($item->options);
    $records = isset($options['records']) ? $options['records'] : [];
    $answer = $answer->value;
    $html = '';
    foreach($records as $key => $name)
    {
        if($key==(int)$answer)
            $html .= '<div><i class="material-icons uk-text-success m-r">check</i>'.$name.'</div>';
        else
            $html .= '<div><i class="material-icons uk-text-default m-r">close</i>'.$name.'</div>';
    }
    return
        '<div class="uk-grid">
        <div class="uk-width-1-1">
            '.$html.'
        </div>
    </div>';
});

HTML::macro('mdShowText', function($answer,$item){
    return
        '<div class="uk-grid">
        <div class="uk-width-1-1">
            <div class="uk-text-primary font-bold">'.($answer && $answer->value?$answer->value:'N/A No data').'</div>
        </div>
    </div>';
});

HTML::macro('mdShowTextarea', function($answer,$item){
    return
    '<div class="uk-grid">
        <div class="uk-width-1-1">
            <div class="uk-text-primary font-bold">'.($answer && $answer->value?$answer->value:'N/A No data').'</div>
        </div>
    </div>';
});

HTML::macro('mdShowParagraph', function($item){
    return
    '<div class="uk-grid">
        <div class="uk-width-1-1">
        </div>
    </div>';
});

HTML::macro('mdShowSignature', function($form){
    return $form -> signature ?
    '<div class="uk-grid">
        <div class="uk-width-1-1 uk-text-center">
            <div class="center" style="max-width:525px;margin:0 auto">
            <img width="100%" height="200" src="'.$form->signature.'"/></div>
            <div>created at: <span class="font-bold">'.$form->created_at().'</span></div>
        </div>
    </div>' : '';
});

HTML::macro('mdShowSignatureItem', function($answer,$item){

    $options = unserialize($answer->value);
    $signature = (isset($options['signature'])&& !empty($options['signature']['sign'])) ? $options['signature'] : null;
    return $signature ?
        '<div class="uk-grid">
        <div class="uk-width-1-1 uk-text-center">
             <div class="center" style="max-width:525px;margin:0 auto">
            <img width="100%" height="200" src="'.$signature['sign'].'"/></div>
            <div>created at: <span class="font-bold">'.$answer->created_at().'</span></div>
        </div>
    </div>' : '<div class="text-primary font-bold">N/A, No Signature</div>';
});

HTML::macro('mdShowCompliant', function($answer,$item)
{
    $title = 'Form data have been labeled as:';
    $options = unserialize($answer -> options);
    $compliant = isset($options['compliant']) ? $options['compliant'] : 'N/A';
    $target = $compliant == 'yes' ? '<span class="font-bold uk-text-success">Compliant</span>':'<span class="font-bold text-danger">Non compliant</span>';
    return $target ?
        '<div class="uk-grid">
        <div class="uk-width-1-1">
                <h4 class="text-primary font-bold">'.$title. ' '.$target.'</h4>
            </div>
        </div>' : '';
});
*/