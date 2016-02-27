<?php namespace Sections\LocalManagers;

class CheckList extends LocalManagersSection {

    public $unitStaffs;

    public function __construct()
    {
        parent::__construct();
        $this -> unitStaffs = \Model\Staffs::whereUnitId(\Auth::user()->unitId())->get();
        $this -> unitForms = \App::make('\Model\AssignedForms') -> getFormsBySelect([2,3],'units');
        $this -> genericForms = \App::make('\Model\AssignedForms') -> getFormsBySelect([2,3],'generic');
        $this -> breadcrumbs -> addCrumb('check-list', 'Check list');
    }

    public function getIndex()
    {
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('list') );
        return \View::make($this->regView('submitted.list'), compact('breadcrumbs'));
    }

    public function old_getIndex(){
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('list') );
        return \View::make($this->regView('index'), compact('breadcrumbs'));
    }

    public function getCreate()
    {
        list($start,$end) = (explode(',',base64_decode(\Input::get('dates'))));
        $start  = \Carbon::parse(date('Y-m-d H:i:s', ($start/1000)), \Auth::user()->timezone);
        $end    = \Carbon::parse(date('Y-m-d H:i:s', ($end/1000)), \Auth::user()->timezone);
        $day    = (($start->format('H:i:s') == '00:00:00') && ($end->format('H:i:s') == '00:00:00')) == 'true' ? 1 : 0;
        $data   = ['s'=>$start,'e'=>$end,'d'=>$day];
        $genericForms  = $this -> genericForms;
        $unitForms     = $this -> unitForms;
        $forms         = $genericForms -> merge($unitForms) -> sortByDesc('id');
        $staff         = $this -> unitStaffs;
        $breadcrumbs   = $this -> breadcrumbs -> addLast( $this -> setAction('create') );
        return \View::make($this->regView('create'), compact('forms','staff','data','breadcrumbs'));
    }

    public function postCreate()
    {
        if (!\Request::ajax())
            return $this -> redirectIfNotExist();
        $rules = $this -> getIndividualRules();

        $input = \Input::all();
        $new   = new \Model\CheckListTasks();

        $rules = array_merge($rules,$new -> rules);
        $validator = \Validator::make($input, $rules);

        if(!$validator -> fails()) {

            $new -> fill($input);
            $new -> unit_id  = $this->auth_user->unitId();
            $new -> tz = \Auth::user()->timezone;
            $save = $new -> save();
            if($save) {
                \Services\CheckList::createTasksItems($input, $new);
                return \Response::json(['type' => 'success', 'msg' => \Lang::get('/common/messages.create_success')]);
            }
            else
                return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.create_fail')]);
        }
        else
        {
            $errMsg = $this->ajaxErrors($validator->messages()->toArray(),[]);
            return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.create_fail'), 'errors' => $errMsg]);
        }
    }

    public function postEdit($id)
    {
        if(!\Request::ajax())
            return $this -> redirectIfNotExist();
        $task = \Model\CheckListTasks::find($id);
        $rules = $this -> getIndividualRules();
        $rules = array_merge($rules, $task->rules);
        $input    = \Input::except('all_day');
        unset($rules['start'],$rules['end'],$rules['all_day'],$rules['repeat']);
        unset($input['start'],$input['end'],$input['all_day'],$input['repeat']);
        $validator = \Validator::make($input, $rules);
        if(!$validator -> fails()) {
            $task -> fill($input);
            $update = $task -> update();
            if($update) {
                return \Response::json(['type' => 'success', 'msg' => \Lang::get('/common/messages.update_success')]);
            }
            else
                return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.update_fail')]);
        }
        else
        {
            $errMsg = $this->ajaxErrors($validator->messages()->toArray(),[]);
            return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.update_fail'), 'errors' => $errMsg]);
        }
    }

    public function getEdit($id)
    {
        if(!\Request::ajax())
            return $this->redirectIfNotExist();
        $item = \Model\CheckListItems::find($id);
        $task = $item->task;

        if(!$task || !$task -> checkAccess())
            return \Response::json(['type'=>'fail', 'msg'=>\Lang::get('/common/messages.not_exist')]);

        $genericForms  = $this -> genericForms;
        $unitForms     = $this -> unitForms;
        $forms         = $genericForms -> merge($unitForms) -> sortByDesc('id');
        $staff         = $this -> unitStaffs;
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('edit') );
        return \View::make($this->regView('edit'), compact('task','item','staff','forms','breadcrumbs'));
    }

    public function getDelete($id)
    {
        $task = \Model\CheckListTasks::find($id);
        if(!$task || !$task -> checkAccess())
            return $this->redirectIfNotExist();

        $ot = $task -> outstandingTasks();
        $items = $task -> items();
        if($ot->count()){
            $ot->delete();
        }
        if($items->count()){
            $items->delete();
        }
        $delete = $task->delete();

        if ($delete)
            return \Redirect::to('/new-compliance-diary')->with('success', \Lang::get('/common/messages.delete_success'));
        else
            return \Redirect::to('/new-compliance-diary')->with('fail', \Lang::get('/common/messages.delete_fail'));
    }

    public function getForms()
    {
        $navitasForms = \Model\Forms::whereIn('id', function($query){
            $query->select('form_id')->from('assigned_forms')->whereData('generic');
        })->whereIn('assigned_id', [2,3])->where('active', 1)->orderBy('id','DESC')->get();
        $unitForms = $this -> unitForms;
        $breadcrumbs = $this -> breadcrumbs -> addLast(  $this -> setAction('Forms list',false) );
        return \View::make($this->regView('check_task'), compact('unitForms','navitasForms','breadcrumbs'));
    }

    public function getComplete($id)
    {
        if(!\Request::ajax())
            return $this->redirectIfNotExist();
        $check_task_item = $item = \Model\CheckListItems::find($id);
        $task = $item -> task;
        if(!$task || !$task -> checkAccess())
            return \Response::json(['type'=>'fail', 'msg'=>\Lang::get('/common/messages.not_exist')]);

        if($task -> form) {
            $submitted = $item->getLastSubmitted();
            if (!$submitted) {
                $user = $this->auth_user;
                $form = \Model\Forms::with('items')->find($task->form_id);
                if (!$form || (($unitId = $form->unit_id) && ($unitId !== $user->unitId())))
                    return \Response::json(['type' => 'fail', 'msg' => \Lang::get('/common/messages.not_exist')]);
                $this->setAction('create');
                \View::addNamespace('Sections\LocalManagers\FormsManager', app_path() . '/Sections/LocalManagers/FormsManager/views');
                return \View::make('Sections\LocalManagers\FormsManager::common.modal.create', compact('form', 'check_task_item', 'breadcrumbs'));
            } else{
                return \Redirect::action('Sections\LocalManagers\FormsManager@getResolve', [$submitted->form_answer_id]);
            }
        }
        else {
            $breadcrumbs = $this->breadcrumbs->addLast($this->setAction('summary'));
            return \View::make($this->regView('completed'), compact('task', 'item', 'breadcrumbs'));
        }
    }

    public function postComplete($id)
    {
        if(!\Request::ajax())
            return $this->redirectIfNotExist();

        $item = \Model\CheckListItems::find($id);
        $task = $item -> task;
        if(!$task || !$task -> checkAccess())
            return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.not_exist')]);

        $user = $this -> auth_user;
        $unit = $user -> unit();

        if($task->staff)
            \Input::merge(['staff_name' => $task->staff->fullname()]);
        if($task->form)
            \Input::merge(['form_name' => $task->form->name]);
        \Input::merge(['title' => $task->title]);
        \Input::merge(['description' => $task->description]);
        \Input::merge(['completed' => (\Input::get('completed') ? 1 : 0)]);
        \Input::merge(['task_item_id' => $item->id]);
        \Input::merge(['start' => $item->start]);
        \Input::merge(['end' => $item->end]);
        \Input::merge(['all_day' => $task->all_day]);
        $input    = \Input::all();

        $submitted = new \Model\CheckListSubmitted();
        $rules = $submitted-> rules;

        $validator = \Validator::make($input, $rules);
        if(!$validator -> fails()) {
            $submitted -> fill($input);
            $submitted -> unit_id = $unit -> id;
            $save = $submitted -> save();
            if($save) {
                \Services\FilesUploader::updateAfterCreate(['check_list_items', $user->id, $unit->id, $submitted->id]);
                if($ot = $item->outstandingTask){
                    $ot -> delete();
                }
                return \Response::json(['type'=>'success', 'msg'=>\Lang::get('/common/messages.action_successful')]);
            }
            else
                return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.action_failed')]);
        }
        else
        {
            $errMsg = $this->ajaxErrors($validator->messages()->toArray(),[]);
            return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.action_failed'), 'errors' => $errMsg]);
        }
    }

    public function getSubmittedList()
    {
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('list') );
        return \View::make($this->regView('submitted.list'), compact('breadcrumbs'));
    }

    public function getDatatable()
    {
        if(!\Request::ajax())
            return $this -> redirectIfNotExist();
        $unitId = \Auth::user() -> unit() -> id;
        $submitted = \Model\CheckListSubmitted::whereUnitId($unitId)->orderBy('id','desc')->get();
        $submitted = ($filter = \Input::get('datatable')) ? \Mapic::datatableFilter($filter, $submitted) : $submitted->take(100);
        $options = [];
        if($submitted)
            foreach ($submitted as $record)
            {
                $title = '<span class="font-bold">'.$record -> title.'</span>';

                if($record -> form_name)
                    $title = $title.('<BR>'.'<small class="uk-text-muted">Form: '.$record -> form_name.'</small>');
                if($record -> staff_name)
                    $title = $title.('<BR>'.'<small class="uk-text-muted">Staff: '.$record -> staff_name.'</small>');
                $details = $record -> form_answer_id ? 'form-details' : 'log-details';
                $options[] = [
                    strtotime($record -> created_at),
                    $record->created_at(),
                    $title,
                    $record->getSchedulesDate(),
                    ($record -> completed ? '<span class="font-bold uk-badge uk-badge-success">Completed</span>' : '<span class="font-bold uk-badge uk-badge-danger">Not Completed</span>'),
                    '<div class="uk-text-center">'.
                        \HTML::mdActionButton($record->id.'/'.$details,'check-list','submitted','search').
                    '</div>',
                ];
            }
        return \Response::json(['aaData' => $options]);
    }

    public function getSubmittedDetails($id,$destin)
    {
        $this->breadcrumbs->addCrumb('/check-list/submitted/', 'Submitted list');
        $breadcrumbs = $this->breadcrumbs->addLast($this->setAction('Submitted task details', false));
        $submitted = \Model\CheckListSubmitted::find($id);
        if($ansId = $submitted -> form_answer_id){
            $answer = \Model\FormsAnswers::with('formLog')->find($ansId);
            \View::addNamespace('Sections\LocalManagers\FormsManager', app_path() . '/Sections/LocalManagers/FormsManager/views');
            return \View::make('Sections\LocalManagers\FormsManager::common.default.details', compact('answer', 'breadcrumbs'));
        }
        else{
            return \View::make($this->regView('submitted.details'), compact('submitted', 'breadcrumbs'));
        }
    }

    public function getSubmittedDelete($id,$destin)
    {
        $submitted =  \Model\CheckListSubmitted::find($id);
        if($destin == 'form') {
            $submitted->formAnswer->delete();
        }
        $submitted -> delete();
        return \Redirect::to('/check-list/submitted')->with('success', \Lang::get('/common/messages.delete_success'));
    }


    public function getIndividualRules()
    {
        $rules = [];

        \Input::merge(['all_day' => (\Input::get('all_day') ? 1 : 0) ]);

        if(!is_numeric(\Input::get('staff_id')))
            \Input::merge(['staff_id' => NULL]);

        if(!\Input::get('weekend'))
            \Input::merge(['weekend' => 0]);

        if(!is_numeric(\Input::get('form_id')))
            \Input::merge(['form_id' => NULL]);

        if(in_array(\Input::get('repeat'),['day','week','month']))
        {
            $rules['repeat_freq'] = 'required';
            $rules['repeat_to']   = 'required';
            if(!in_array(\Input::get('repeat_freq'),range(1, 6))){
                \Input::merge(['repeat_freq' => 1]);
            }
            $repeat_to = \Carbon::parse(\Input::get('repeat_to'))->addDays(1)->setTime(0,0,0);
            $start = \Carbon::parse(\Input::get('start'));
            \Input::merge(['repeat_to' => ( ($start >= $repeat_to) ? \Input::get('end') : $repeat_to ) ]);
        }
        else{
            \Input::merge(['repeat_freq' => NULL, 'repeat_to' => NULL]);
        }



        if(!in_array(\Input::get('type'),['default','high','medium','low']))
            \Input::merge(['type' => '']);

        if(!in_array(\Input::get('repeat'),['none','day','week','month']))
            \Input::merge(['repeat' => '']);
        return $rules;
    }

    public function getDeleteByStaff(){

        $staffIds = \Model\CheckListTasks::whereUnitId($this->auth_user->unitId())->lists('staff_id');
        if(!$staffIds)
            return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.empty_data')]);
        $staff =  \Model\Staffs::where('unit_id','=',$this->auth_user->unitId())->whereIn('id',$staffIds)->get();
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('delete') );
        return \View::make($this->regView('modal.delete-by-staff'), compact('staff','breadcrumbs'));
    }

    public function postDeleteByStaff()
    {
        if(!\Request::ajax())
            return $this->redirectIfNotExist();
        $staff =  \Model\Staffs::find(\Input::get('staff_id'));
        if(!$staff || !$staff -> checkAccess())
            return $this->redirectIfNotExist();
        $schedule = \Model\CheckListTasks::whereUnitId($this->auth_user->unitId())->whereStaffId($staff->id);
        if($schedule->count()){
            foreach($schedule->get() as $task){
                $ot =  $task -> outstandingTasks();
                if($ot->count()){
                    $ot->delete();
                }
            }
        }
        $delete = $schedule->count() ? $schedule -> delete() : false;
        if($delete)
            return \Response::json(['type'=>'success', 'msg'=>\Lang::get('/common/messages.delete_success')]);
        else
            return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.delete_fail')]);
    }

    function getClassName($type = 'default'){
        switch($type){
            case 'default'  :return 'b-l b-2x b-warning'; break;
            case 'high'     :return 'b-l b-2x b-danger';  break;
            case 'medium'   :return 'b-l b-2x b-warning'; break;
            case 'low'      :return 'b-l b-2x b-success'; break;
            default: return 'b-l b-2x b-primary'; break;
        }
    }
}