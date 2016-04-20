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
