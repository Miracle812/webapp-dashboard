<?php namespace Sections\LocalManagers;

class FormProcessor extends LocalManagersSection {

    public $types = [
        'input'      => 'fa-arrows',
        'textarea'   => 'fa-align-justify',
        'paragraph'  => 'fa-align-left',
        'radio'      => 'fa-dot-circle-o',
        'checkbox'   => 'fa-check-square-o',
        'select'     => 'fa-caret-square-o-down',
        'multiselect'=> 'fa-level-down',
        'datepicker' => 'fa-calendar'
    ];

    public function __construct(){
        parent::__construct();
    }

    public function getUpload($idForm,$idItem)
    {
        $form   = \Model\Forms::find($idForm);
        $item  = \Model\FormsItems::find($idItem);
        $user   = \Auth::user();
        $files = \Model\FormsFiles::whereFormLogId($form->id)->whereItemLogId($item->id)->whereUserId($user->id)->whereUnitId($user->unit()->id)->whereNull('answer_id')->get();
        $html = '';
        foreach($files as $file) {
            $html .= $this->getFileThumb($file);
        }
        return $html;
    }

    public function getFileDownload($id) //ajax
    {
        $file = \Model\FormsFiles::find($id);
        if(!$file || !$file -> checkAccess())
            return \Response::json(['type'=>'fail', 'msg'=>\Lang::get('/common/messages.not_exist')]);
        else{
            $destinationPath = public_path() . "/$file->file_path/";
            $file = $destinationPath . $file->file_name;
            return \Response::download($file);
        }
        return \Response::json(['type'=>'fail', 'msg'=>\Lang::get('/common/messages.not_exist')]);
    }

    public function getFileDisplay($id) //ajax
    {
        $file = \Model\FormsFiles::find($id);
        if(!$file || !$file -> checkAccess())
            return \Response::json(['type'=>'fail', 'msg'=>\Lang::get('/common/messages.not_exist')]);
        else{
            $file = $file->file_path . $file->file_name;
            return \URL::to($file);
        }
        return \Response::json(['type'=>'fail', 'msg'=>\Lang::get('/common/messages.not_exist')]);
    }

    public function getFileDelete($id)
    {
        $file = \Model\FormsFiles::find($id);
        if(!$file || !$file -> checkAccess())
            return \Response::json(['type'=>'fail', 'msg'=>\Lang::get('/common/messages.not_exist')]);
        \File::delete(public_path().$file -> file_path.$file -> file_name);
        if($file -> delete())
            return \Response::json(['type'=>'success', 'msg'=>\Lang::get('/common/messages.delete_success')]);
        else
            return \Response::json(['type'=>'fail', 'msg'=>\Lang::get('/common/messages.delete_fail')]);
    }

    public function getFileThumb($file)
    {
        $name = $file->file_name;
        $ext = pathinfo($name, PATHINFO_EXTENSION);

        $href = '#';
        $images = false;
        $short = (strlen($name) > 20) ? substr($name, 0, 20) . '_.'.$ext : $name;
        switch ($ext){
            case 'jpg': case 'jpeg': case 'png': case 'gif': case 'bmp':
                $ico = 'fa-file-image-o';
                $href = \URL::to($file->file_path.$name);
                $images = true;
            break;

            case 'doc': case 'docx':  case 'odt': case 'rtf':
                $ico = 'fa-file-word-o';
            break;

            case 'xls': case 'xlsx':
                $ico = 'fa-file-excel-o';
            break;
            case 'pdf':
                $ico = 'fa-file-pdf-o';
            break;
            case 'txt':
                $ico = 'fa-file-text-o';
            break;
            default :  return '';   break;
        }
        return '
        <div class="col-sm-3 m-b">
            <div data-toggle="dropdown" class="btn btn-default tooltip-link" href="'.$href.'" title = "'.$short.'">
                <i class="fa fa-5x '.$ico.'"></i>
            </div>
            <ul class="dropdown-menu">'.
                ($images ? '<li><a  class="form-file-display" href="'.\URL::to($href).'"><span  class="text-primary"><i class="fa fa-search m-r"></i></span>Display</a></a></li>':'').
                '<li><a  class="form-file-download" href="/form-processor/file/download/'.$file->id.'"><span  class="text-success"><i class="fa fa-download m-r"></i></span>Download</a></a></li>
                <li class="divider"></li>
                <li><a  class="form-file-delete" href="/form-processor/file/delete/'.$file->id.'"><span  class="text-danger"><i class="fa fa-times-circle-o m-r"></i></span>Delete</a></li>
            </ul>
        </div>';
    }


    public function postUpload($idForm,$idItem)
    {
        $form = \Model\Forms::find($idForm);
        $item = \Model\FormsItems::find($idItem);
        $identifier = $idForm.'_'.$this->auth_user->id;
        $option = unserialize($item -> options);
        $extensions = $option['extensions'];
        $fileSize = number_format($option['file_size'],1)*1024;

        $identifier = \Session::get('form_manager.upload.identifier') ? : $identifier;
        if(!\Session::has('form_manager.upload.identifier'))
            \Session::put('form_manager.upload.identifier', $identifier);

        $photo = new \Services\FilesUploader('form_manager');
        $file_path = $photo -> getUploadPath($identifier,'files');
        $file = \Input::file('Filedata');

        if (!in_array($ext=strtolower($file -> getClientOriginalExtension()), $extensions))
            return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.create_fail'), 'errors' => ["files_upload[$form->id][$item->id]"=>['Not permitted file type (.'.$ext.'): '.$file -> getClientOriginalName().'.']]]);

        if( ($file->getSize()/1024) > $fileSize )
            return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.create_fail'), 'errors' => ["files_upload[$form->id][$item->id]"=>['Allowed file size exceeded for file: '.$file -> getClientOriginalName().'.']]]);
        $file_name = $photo -> Uploadify($file,$file_path,$extensions);
        $user   = \Auth::user();
        $create = ['user_id' => $user -> id,'unit_id' => $user -> unit() -> id,'form_log_id' => $form->id,'item_log_id' => $item->id,'answer_id' => null,'file_name' => $file_name,'file_path' => $file_path];
        $files  = new \Model\FormsFiles();
        $files -> fill ($create);
        $save = $files -> save();
        if($save && $file_name)
            \Session::push('form_manager.files.items', [$file_path,$file_name]);
        return \Response::json(['type'=>'success']);
    }

    public function postData()
    {
        if(!\Request::ajax()) {
            //return $this -> redirectIfNotExist();
            //return \Response::json(['type'=>'fail', 'msg'=>\Lang::get('/common/messages.not_exist')]);
        }
        $user = \Auth::user();
        $rules = $attrNames = $options = [];
        $inputs = \Input::except('_token');
        $form = $this->getFormFromInputs($inputs);
        if($form){
            $items = $form -> items;
            $filesUpload = [];
            foreach($items as $item){
                if ($item -> type == 'files_upload')
                    $filesUpload[] = $item->id;

                if($item -> required){
                    if($item->type == 'signature'){
                        $rules[$item->type.'.'.$form->id.'.'.$item->id.'.sign'] = 'required';
                        $attrNames[$item->type . '.' . $form->id . '.' . $item->id.'.sign'] = 'signature';
                    }
                    else {
                        $rules[$item->type . '.' . $form->id . '.' . $item->id] = 'required';
                        $attrNames[$item->type . '.' . $form->id . '.' . $item->id] = $item->label;
                    }
                }
            }
            $validator = \Validator::make($inputs, $rules);
            $validator -> setAttributeNames($attrNames);
            $errors = $validator -> messages() -> toArray();
            if(!$validator -> fails())
            {
                $formLog    = \Model\FormsLogs::firstOrCreate(['assigned_id'=>$form->assigned_id, 'name'=>$form->name, 'description'=>$form->description,'group'=>$form->group ? $form->group->name : NULL]);
                //$options['compliant'] = \Input::get('compliant') ? : NULL;
                $targetType = \Input::get('target_type') ? : NULL;
                $targetId   = \Input::get('target_id') ? : NULL;

                if($assId = \Input::get('cleaning_schedule.id')){
                    $assigned = 'new_cleaning_schedules_items2,'.$assId;
                }
                elseif($assId = \Input::get('check_task.id')){
                    $assigned = 'check_list_items,'.$assId;
                }
                else{
                    $assigned = NULL;
                }
                $answer     = \Model\FormsAnswers::create(['unit_id'=>$user->unitId(),'target_id'=>$targetId,'assigned'=>$assigned,'target_type'=>$targetType,'form_log_id'=>$formLog  -> id, 'options'=>serialize($options)]);
                $chkCompliant = [];
                foreach($items as $item){
                    $itemLog = \Model\FormsItemsLogs::firstOrCreate(['form_log_id'=>$formLog->id, 'org_id'=>$item->id, 'parent_id'=>$item->parent_id,'required'=>$item->required, 'label'=>$item->label, 'description'=>$item->description, 'type'=>$item->type, 'sort'=>$item->sort, 'options'=>$item->options]);
                    $itemsLogs[] = $itemLog->id;
                    if(in_array($item->type,['tab','paragraph','submit_button', 'compliant','assign_staff'])){
                        continue;
                    }
                    if($item->type == 'files_upload'){
                        if(count($filesUpload)){
                            \Model\FormsFiles::whereFormLogId($form->id)->whereItemLogId($item->id)->whereNull('answer_id')->update(['answer_id'=>$answer->id,'item_log_id'=>$itemLog->id,'form_log_id'=>$formLog->id ]);
                            \Session::forget('form_manager.upload.identifier');
                        }
                        continue;
                    }
                    $fieldName = $item -> type.'.'.$form -> id.'.'.$item->id.'';
                    if($item -> type == 'signature'){
                        $signature = ['signature'=>[
                            'sign' => \Input::get($fieldName.'.sign'),
                        ]];
                        \Model\FormsAnswersValues::create(['unit_id'=>$user->unitId(),'answer_id'=>$answer->id,'item_log_id'=>$itemLog->id,'value'=>serialize($signature)]);
                    }
                    else{
                        $fieldValue = \Input::has($fieldName) ? \Input::get($fieldName) : NULL;
                        if($fieldValue) {
                            if (is_array($fieldValue)) {
                                $arrVal = [];
                                foreach($fieldValue as $optionId => $fValue){
                                    $arrVal[$optionId] = $fValue;
                                }
                                \Model\FormsAnswersValues::create(['unit_id'=>$user->unitId(),'answer_id'=>$answer->id,'item_log_id'=>$itemLog->id,'value'=>serialize($arrVal)]);
                            }
                            else{
                                \Model\FormsAnswersValues::create(['unit_id'=>$user->unitId(),'answer_id'=>$answer->id,'item_log_id'=>$itemLog->id,'value'=>$fieldValue]);
                            }

                            if($item -> type == 'yes_no'){
                                if(in_array('no',$fieldValue)){
                                    $chkCompliant[] = 'no';
                                }
                                elseif(in_array('yes',$fieldValue)){
                                    $chkCompliant[] = 'yes';
                                }
                            }
                        }
                        else {
                            \Model\FormsAnswersValues::create(['unit_id' => $user->unitId(), 'answer_id' => $answer->id, 'item_log_id' => $itemLog->id, 'value' => $fieldValue]);
                        }
                    }
                }
                $compliant = ['compliant'=>(in_array('no',$chkCompliant) ? 'no' : 'yes')];
                $options = array_merge(['items_logs_ids'=>$itemsLogs],$compliant);
                $answer->update(['options'=>serialize(array_merge(unserialize($answer->options), $options))]);
                //[1 => 'health_questionnaires', 2 => 'check_list_daily', 3 => 'check_list_monthly' , 4=>'cleaning_schedule'];
                switch($form -> assigned_id){
                    case '4' :
                        \Services\CleaningSchedule::submitForm($inputs, $answer->id);
                        $updatedAnswer = \Model\FormsAnswers::find($answer->id);
                        \Services\OutstandingTasks::create($updatedAnswer,$inputs);
                        break;
                    case '2' :
                    case '3' :
                        \Services\CheckList::submitForm($inputs, $answer->id);
                        \Services\OutstandingTasks::create($answer,$inputs);
                        break;
                }
                return \Response::json(['type'=>'success', 'msg'=>\Lang::get('/common/messages.create_success')]);
            }
            else
            {
                if(\Request::ajax() && $errors){

                    $errors = $this->formsErrorsConverter($errors);
                }
                return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.update_fail'), 'errors' => $this->ajaxErrors($errors,[])]);
            }
            return \Response::json(['type'=>'success', 'msg'=>'Forms with items was checked. Test ok.']);
        }
        return \Response::json(['type'=>'error', 'msg'=>'Forms without data can\'t be stored. Please add items to form.']);
    }

    public function formsErrorsConverter($errors){
        $out = [];
        foreach($errors as $key => $value){
            $newKey = '';
            $explode = explode('.', $key);
            $count = count($explode);
            if($count < 2)
                $newKey = $key;
            else {
                for ($i = 0; $i < $count; $i++) {
                    if ($i == 0) {
                        $newKey .= $explode[$i] . '[';
                    }
                    if ($i > 0 && $i < $count - 1) {
                        $newKey .= $explode[$i] . '][';
                    }
                    if ($i == $count - 1) {
                        $newKey .= $explode[$i] . ']';
                    }
                }
            }
            $out[$newKey] = $value;
        }
        return $out;
    }

    public function postResolve($id)
    {
        $inputs = \Input::except('_token');
        $answer = \Model\FormsAnswers::find($id);

        $signIdent = 'signature.'.$answer->id;

        $rules['comment.'.$answer->id] = $rules[$signIdent.'.sign'] = 'required';

        $attrNames['comment.'.$answer->id] = 'comment';
        $attrNames[$signIdent.'.sign'] = 'signature';
        $validator = \Validator::make($inputs, $rules);
        $validator -> setAttributeNames($attrNames);
        $errors = $validator -> messages() -> toArray();
        if($errors){
            return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.update_fail'), 'errors' => $this->ajaxErrors($this->formsErrorsConverter($errors),[])]);
        }
        else {
            $text = '';
            $resolveTypes = ['yes_no'];
            foreach ($resolveTypes as $type) {
                $answerTypes = \Input::get($type);
                foreach ($answerTypes as $idAns => $values) {
                    $checkUpdated = 0;

                    foreach ($values as $idVal => $data2) {
                        $answerValues = \Model\FormsAnswersValues::find($idVal);
                        $itemName = 'Item: ' . $answerValues->itemLog->label . ': <br>';
                        $options = unserialize($answerValues->itemLog->options);
                        $oldValues = unserialize($answerValues->value);
                        $update = [];
                        $optionsNames = '';
                        foreach ($data2 as $key => $val) {
                            if ($val == 'yes') {
                                $update[$key] = $val;
                                $optionsNames .= ' (Option: '.$options['records'][$key] . ' from "NO" answer to "YES"). <br>';
                            }
                        }
                        if (count($update)) {
                            $text .= $itemName .' '. $optionsNames;
                            $updatedValues = serialize(array_replace($oldValues,$update));
                            $answerValues->value = $updatedValues;
                            $answerValues->update();
                            $checkUpdated += 1;
                        }
                    }
                    if ($checkUpdated > 0){

                        if(!$answer -> getComplaintsAnswers() || ($answer -> getComplaintsAnswers() -> count() == 0)){
                            $options = unserialize($answer->options);
                            $options['compliant'] = 'yes';
                            $answer -> options = serialize($options);
                            $update = $answer -> update();
                            if($update){
                                $assigned =  ($answer -> assigned) ? explode(',',$answer -> assigned) : [];
                                if(count($assigned) == 2){
                                    list($section,$itemId) = $assigned;
                                    \Model\OutstandingTask::where(function ($query) use($section,$itemId){
                                        $query->whereTargetType($section)->whereTargetId($itemId);
                                    })->orWhere(function ($query) use($answer){
                                        $query->whereTargetType('forms_answers')->whereTargetId($answer->id);
                                    })->delete();
                                    $submitted = null;
                                    switch ($section){
                                        case 'new_cleaning_schedules_items2' : $submitted = \Model\NewCleaningSchedulesSubmitted::whereFormAnswerId($answer->id)->whereTaskItemId($itemId)->first(); break;
                                        case 'check_list_items' : $submitted = \Model\CheckListSubmitted::whereFormAnswerId($answer->id)->whereTaskItemId($itemId)->first(); break;
                                    }
                                    if($submitted){
                                        $submitted->update(['completed'=>1]);
                                        $item  = $submitted -> item;
                                        if($item){
                                            $item->delete();
                                        }
                                    }
                                }
                            }
                        }
                        \Model\FormsAnswersUpdates::create([
                            'unit_id' => $answer->unit_id,
                            'answer_id' => $answer->id,
                            'changes' => $text,
                            'comment' => \Input::get('comment.' . $answer->id),
                            'signature' => \Input::get('signature.' . $answer->id . '.sign')
                        ]);
                        return \Response::json(['type'=>'success', 'msg'=>'Forms data has been updated']);
                    } else {
                        return \Response::json(['type'=>'info', 'msg'=>'Nothing to update']);
                    }
                }
            }
        }
    }

    public function getFormFromInputs($inputs)
    {
        if(isset($inputs['_token']))
            unset($inputs['_token']);
        $id = isset($inputs['form_base_id']) ? $inputs['form_base_id'] : NULL;
        if(!$id)
        foreach($inputs as $key => $data1){
            if(is_array($data1)){
                foreach($data1 as $formId => $data2){
                    $id = is_int($formId) ? $formId : 0;
                    if($id > 0) continue;
                }
            }
        }
        return $id ? \Model\Forms::find($id) : null;
    }





    public function getDatatable()
    {
        $options=[];
        $user = $this -> auth_user;
        $forms = \Model\Forms::where('unit_id','=',$user->unitId())->get();
        if ($forms -> count()){
            foreach ($forms as $row)
            {
               // if($row->{$row->assigned}) {
                    $options[] = [
                        strtotime($row->created_at),
                        $row->created_at(),
                        $row->name,
                        $row->assigned ? \Lang::get('/common/general.form_builder.'.$row->assigned) : 'N/A, Not assigned',
                        \HTML::mdOwnOuterBuilder(\HTML::mdOwnIcoStatus($row->active)),
                        \HTML::mdOwnOuterBuilder(
                            \HTML::mdOwnModalButton($row -> id.'/display','form-builder','form','search','btn-default m-r').
                            \HTML::mdOwnButton($row -> id.'/edit', 'form-builder','form','edit','btn-primary m-r').
                            \HTML::mdOwnButton($row -> id, 'form-builder','delete','clear','btn-danger')
                        )
                    ];
              //  }
            }
            return \Response::json(['aaData' => $options]);
        }
        return \Response::json(['aaData' => $options]);
    }

    public function getIndex()
    {
        $types = $this->types;
        $form = \Model\Forms::find(1);
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('list') );
        return \View::make($this->regView('index'), compact('types', 'breadcrumbs', 'form'));
    }

    public function getDisplay($id)
    {
        $form = \Model\Forms::with('items')->find($id);
        if(!$form || !$form -> checkAccess())
            return \Response::json(['type'=>'fail', 'msg'=>\Lang::get('/common/messages.not_exist')]);


        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('display') );
        return \View::make($this->regView('modal.display'), compact('breadcrumbs', 'form'));
    }

    public function getCreate()
    {
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('list') );
        return \View::make($this->regView('create'), compact('breadcrumbs'));
    }

    public function postCreate()
    {
        \Input::merge(['active'=>(\Input::get('active')?1:0)]);
        $input     = \Input::all();
        $new       = new \Model\Forms();
        $rules     = [
            'name'=>'required|between:1,50',
            'description'=>'between:1,255',
            'assigned'=>'required',

        ];
        $validator = \Validator::make($input, $rules);
        if(!$validator -> fails())
        {
            $new -> fill($input);
            $new -> unit_id = \Auth::user()->unit()->id;
            $save = $new -> save();
            $type = $save ? 'success' : 'fail';
            return \Redirect::to('/form-builder/')->with($type, \Lang::get('/common/messages.create_'.$type));
        }
        else
        {
            return \Redirect::back()->withInput()->withErrors($validator);
        }
    }

    public function getForm($id)
    {
        $form = \Model\Forms::find($id);
        if(!$form || !$form -> checkAccess())
            return \Response::json(['type'=>'fail', 'msg'=>\Lang::get('/common/messages.not_exist')]);
        $types = $this -> types;
        $form_items = $form -> items;
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('list') );
        return \View::make($this->regView('edit'), compact('types','form','form_items','breadcrumbs'));
    }

    public function getEditItem($id)
    {
        $item = \Model\FormsItems::find($id);
        if(!$item || !$item -> checkAccess())
            return \Response::json(['type'=>'fail', 'msg'=>\Lang::get('/common/messages.not_exist')]);
        $type = $item -> type;
        $form = $item -> form;
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('edit') );
        return \View::make($this->regView('modal.edit.'.$type), compact('item','form','type','breadcrumbs'));
    }

    public function getRules($type, $inputs)
    {
        $rules['label'] = 'required';
        switch($type){
            case 'input':
            case 'datepicker':
            case 'textarea':    $rules['placeholder'] = 'required';break;
            case 'radio':
            case 'checkbox':    $rules["arrangement"] = 'required';
            case 'select':
            case 'multiselect':
                $rules['label'] = 'required';
                if(isset($inputs['options']) && ($options = $inputs['options']) && is_array($options)){
                    foreach($options as $key => $value) {
                        $rules["options.$key"] = 'required';
                    }
                }
                else{
                    $rules["options"] = 'required';
                }
                break;
            //case 'staff':  break;
            //case 'datepicker': break;
            //case 'paragraph':  break;
            //case 'signature':  break;
        }
        return $rules;
    }


    public function postEditItem($id)
    {
        $item = \Model\FormsItems::find($id);
        if(!$item || !$item -> checkAccess())
            return \Response::json(['type'=>'fail', 'msg'=>\Lang::get('/common/messages.not_exist')]);
        $required = \Input::get('required') ? 1 : 0 ;
        $rules = $this->getRules($item->type, $inputs = \Input::all());
        $validator = \Validator::make($inputs, $rules);
        $errors = $validator -> messages() -> toArray();
        if(empty($errors))
        {
            $item -> fill (\Input::all()) ;
            $item -> required = $required;
            if($options = \Input::get('options')){
                $options = array_combine(range(1, count($options)), array_values($options));
                $unserialized = unserialize($item->options);
                $unserialized['records'] = $options;
                $item -> options = serialize($unserialized);
            }
            $item -> update();
            return \Response::json(['type'=>'success', 'msg'=>\Lang::get('/common/messages.update_success')]);
        }
        else
        {
            return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.update_fail'), 'errors' => $this->ajaxErrors($errors,[])]);
        }
    }

    public function postEditForm($id)
    {
        $form = \Model\Forms::find($id);
        if(!$form || !$form -> checkAccess())
            return \Response::json(['type'=>'fail', 'msg'=>\Lang::get('/common/messages.not_exist')]);

        \Input::merge(['active'=>(\Input::get('active')?1:0)]);

        $rules = array(
            'name'       => 'required',
            'assigned'   => 'required',
            'description'=> 'between:1,255'
        );
        $validator = \Validator::make(\Input::all(), $rules);
        $errors = $validator -> messages() -> toArray();
        if(empty($errors) )
        {
            $form -> fill (\Input::all()) ;
            $form -> update();
            return \Response::json(['type'=>'success', 'msg'=>\Lang::get('/common/messages.update_success')]);
        }
        else
        {
            return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.update_fail'), 'errors' => $this->ajaxErrors($errors,[])]);
        }
    }

    public function getAddItem($id,$type)
    {
        if(!\Request::ajax())
            return $this -> redirectIfNotExist();
        $form = \Model\Forms::find($id);
        if(!$form || !$form -> checkAccess() || !isset($this->types[$type]))
            return \Response::json(['type'=>'fail', 'msg'=>\Lang::get('/common/messages.not_exist')]);
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('add') );
        $icon = $this->types[$type];
        return \View::make($this->regView('modal.add.'.$type), compact('form','type', 'icon', 'breadcrumbs'));
    }

    public function postAddItem($id,$type)
    {
        if(!\Request::ajax())
            return $this -> redirectIfNotExist();
        $form = \Model\Forms::find($id);
        if(!$form || !$form -> checkAccess())
            return \Response::json(['type'=>'fail', 'msg'=>\Lang::get('/common/messages.not_exist')]);
        $required = \Input::get('required') ? 1 : 0 ;
        $rules = $this->getRules($type, $inputs = \Input::all());

        $validator = \Validator::make($inputs, $rules);
        $errors = $validator -> messages() -> toArray();
        if(empty($errors))
        {
            $count = $form->items->count();
            $new = new \Model\FormsItems();
            $new -> unit_id = $this->auth_user->unitId();
            $new -> lang = $this->auth_user->lang;
            $new -> form_id = $id;
            $new -> type = $type;
            $new -> sort = $count+1;
            $new -> required = $required;
            $new -> fill (\Input::all()) ;
            if($options = \Input::get('options')){
                $options = array_combine(range(1, count($options)), array_values($options));
                $array['records'] = $options;
                $new -> options = serialize($array);
            }
            $new -> save();
            return \Response::json(['type'=>'success', 'msg'=>\Lang::get('/common/messages.create_success')]);
        }
        else
        {
            return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.create_fail'), 'errors' => $this->ajaxErrors($errors,[])]);
        }
    }

    public function getRefreshItems($idForm)
    {
        if(!\Request::ajax())
            return $this -> redirectIfNotExist();
        $form = \Model\Forms::find($idForm);
        if(!$form || !$form -> checkAccess())
            return \Response::json(['type'=>'fail', 'msg'=>\Lang::get('/common/messages.not_exist')]);
        $html = '';
        $items = $form -> items;

        if($items->count()){
            foreach($items as $item){
                $type = $item -> type;
                $icon  = $this->types[$type];
                $html .= \View::make($this->regView('partials.form-items.item'), compact('item','type', 'icon'))->render();
            }
        }
        return $html;
    }

    public function getGetItem($idForm,$idItem)
    {
        if(!\Request::ajax())
            return $this -> redirectIfNotExist();
        $form = \Model\Forms::find($idForm);
        if(!$form || !$form -> checkAccess())
            return \Response::json(['type'=>'fail', 'msg'=>\Lang::get('/common/messages.not_exist')]);

        $item = \Model\FormsItems::find($idItem);
        if(!$item || !$item -> checkAccess())
            return \Response::json(['nihuja'],302);
        $type = $item->type;
        $icon  = $this->types[$type];
        return \View::make($this->regView('partials.form-items.' . $type), compact('item','type', 'icon'))->render();
    }

    public function postSortUpdate()
    {
        if(!\Request::ajax())
            return $this -> redirectIfNotExist();
        $list = explode(',',\Input::get('list'));
        if(count($list) > 0){
            foreach($list as $key => $id){
                $item = \Model\FormsItems::find($id);
                if($item && $item -> checkAccess()){
                    $item -> sort = $key;
                    $item -> update();
                }
            };
        }
        return \Response::json([1],200);
    }

    public function postEditable($type)
    {
        if(!\Request::ajax())
            return $this -> redirectIfNotExist();
        $id = \Input::get('pk');
        $label = \Input::get('name');
        $value = \Input::get('value');
        $item = \Model\FormsItems::find($id);
        if(!$item || !$item -> checkAccess() || !in_array($type,['label']) || ($label!==$type))
            return \Response::json(['type'=>'fail', 'msg'=>\Lang::get('/common/messages.not_exist')]);
        $item -> $type = $value;
        $update = $item -> update();
        if($update)
            return \Response::json(['type'=>'success','msg'=>\Lang::get('/common/messages.update_success')],200);
        else
            return \Response::json(\Lang::get('/common/messages.update_fail'),302);
    }

    public function postDeleteItem($id)
    {
        $item = \Model\FormsItems::find($id);
        if(!$item || !$item -> checkAccess() )
            return $this -> redirectIfNotExist();

        $form = $item->form;
        $delete = $item -> delete();

        $type = $delete ? 'success' : 'fail';

        return \Redirect::to(\URL::to('/form-builder/form/'.$form->id))->with($type, \Lang::get('/common/messages.delete_'.$type));
    }
}