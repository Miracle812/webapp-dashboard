<?php namespace Sections\LocalManagers;

class NewCleaningSchedule extends LocalManagersSection {

    public $unitStaffs;

    public function __construct()
    {
        parent::__construct();
        $this -> unitStaffs = \Model\Staffs::whereUnitId(\Auth::user()->unitId())->get();
        $assignedForms = new \Model\AssignedForms();
        $this -> unitForms = $assignedForms -> getFormsBySelect(4,'units');
        $this -> breadcrumbs -> addCrumb('new-cleaning-schedule', 'Cleaning schedule');
    }

    public function getIndex(){
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('list') );
        return \View::make($this->regView('index'), compact('breadcrumbs'));
    }

    public function getCreate()
    {
        $utz = \Auth::user()->timezone;
        list($start,$end) = (explode(',',base64_decode(\Input::get('dates'))));
        $start  = \Carbon::parse(date('Y-m-d H:i:s', ($start/1000)), $utz);
        $end    = \Carbon::parse(date('Y-m-d H:i:s', ($end/1000)), $utz);
        if((((int)$start->format('His'))==0)&&(((int)$end->format('His'))==0)){
            if(($diff = $start->diffInMinutes($end)) == 1440) {
                $now = \Carbon::now('UTC')->timezone($utz);
                $day = 1;
                $start->setTime($now->hour, 0, 0);
                $end = $start->copy()->addHours(1);
            }else{
                $day = 1;
                $days = $start->diffInDays($end);
                $start -> startOfDay();
                $end = $start->copy()->addDays($days)->subMinutes(1);
            }
        }
        $data   = ['s'=>$start,'e'=>$end,'d'=>(isset($day)?$day:0)];
        $assignedForms = new \Model\AssignedForms();
        $genericForms  = $assignedForms -> getFormsBySelect(4,'generic');
        $unitForms     = $assignedForms -> getFormsBySelect(4,'units');
        $forms         = $genericForms -> merge($unitForms) -> sortByDesc('id');
        $staff         = $this -> unitStaffs;
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('create') );
        return \View::make($this->regView('create'), compact('forms','staff','data','breadcrumbs'));
    }

    public function postCreate()
    {
        if (!\Request::ajax())
            return $this -> redirectIfNotExist();
        $user = \Auth::user();
        $rules = [
            'title'	=> 'required',
            'description' => 'required',
            'start'	=> 'required',
            'end' => 'required',
            'repeat' => 'required',
            'repeat_every' => 'required|numeric|max:12',
            'repeat_until' => 'required'
        ];
        $errCustom = [];

        if(!\Input::get('is_repeatable')){
            unset($rules['repeat'],$rules['repeat_every'],$rules['repeat_until'],$rules['weekends']);
            \Input::merge(['repeat'=>NULL,'repeat_every'=>NULL,'repeat_until'=>NULL,'weekends'=>NULL]);
        }else{
            $repeatUntil = \Carbon::createFromFormat('d/m/Y',\Input::get('repeat_until'), $user->timezone)->setTime(23,59,59);
            \Input::merge(['repeat_until'=>\Input::get('repeat_until')?($repeatUntil = $repeatUntil->copy()->timezone('UTC')):NULL]);
        }

        if(\Input::get('all_day')){
            $start = \Carbon::createFromFormat('d/m/Y',\Input::get('start'), $user->timezone);
            \Input::merge(['start'=>$start->copy()->startOfDay()->timezone('UTC')]);
            \Input::merge(['end'=>$start->copy()->endOfDay()->timezone('UTC')]);
            unset($rules['end']);
        } else{
            $start = \Carbon::createFromFormat('d/m/Y H:i',\Input::get('start'), $user->timezone);
            $end   = \Carbon::createFromFormat('d/m/Y H:i',\Input::get('end'),   $user->timezone);
            \Input::merge(['start'=>$start->copy()->timezone('UTC')]);
            \Input::merge(['end'=>$end->copy()->timezone('UTC')]);
        }

        if(\Input::get('form_id')=='null'){
            \Input::merge(['form_id'=>NULL]);
        }
        if(\Input::get('staff_id')=='null'){
            \Input::merge(['staff_id'=>NULL]);
        }

        $user = \Auth::user();

        \Input::merge(['unit_id'=>$user->unitId()]);
        \Input::merge(['tz'=>$user->timezone]);

        $input = \Input::all();
        $new   = new \Model\NewCleaningSchedulesTasks();
        $validator = \Validator::make($input, $rules);

        $seod = $start->copy()->endOfDay();
        if((\Carbon::now($user->timezone)->diffInDays($start,false) < 0)) {
            $errCustom["start"] = ["Start date can't be less than current date time."];
        }

        if(isset($end)){
            $eeod = $end->copy()->endofDay();
            if(($eeod->isPast($user->timezone))){
                $errCustom["end"] = ["End date can't be less than current date time."];
            }
        }

        if(isset($repeatUntil)){
            $reod = $repeatUntil->copy()->endofDay();
            if(($reod->isPast($user->timezone))){
                $errCustom["repeat_until"] = ["\"Repeat until\" date can't be less than current date time."];
            }
        }
        if(isset($reod,$seod)){
            if(($seod>=$reod) || (isset($eeod) && ($eeod>=$reod))){
                $errCustom["repeat_until"] = ["\"Repeat until\" date can't be equal/less than start or end date."];
            }
        }
        if(isset($end,$start) && ($start>=$end)){
            $errCustom["start"] = ["Start date can't be equal/greater than end date."];
        }

        if(!$validator -> fails() && !(count($errCustom))) {
            $new -> fill($input);
            $save = $new -> save();
            if($save){
                \Services\CleaningSchedule::createTasksItems($new);
                \Services\CleaningSchedule::createOutstandingTasks();
                return \Response::json(['type' => 'success', 'msg' => \Lang::get('/common/messages.create_success')]);
            }
            else
                return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.create_fail')]);
        }
        else
        {
            $errMsg = $this->ajaxErrors($validator->messages()->toArray(),[]);
            return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.create_fail'), 'errors' => (isset($errCustom) ? array_merge($errMsg,$errCustom) : $errMsg)]);
        }
    }

    public function getData()
    {
        $start = \Carbon::parse(\Input::get('start'));
        $end   = \Carbon::parse(\Input::get('end'));
        $unitId = $this->auth_user->unitId();
        $items = \Model\NewCleaningSchedulesItems::
        where('unit_id','=',$unitId)
            -> where('start','>=', $start)
            -> where('start','<=',$end)
            -> get();
        $out = [];
        foreach($items as $item)
        {
            $task = $item->task;
            $title = ($task->staff?$task->staff->fullname() . ' - ' : '') . $task->title;

            if ($item->isCompleted()) {
                $completed = 'bg-success bg';
            }
            elseif(in_array($item->id, \Services\CleaningSchedule::ongoingTasks($unitId)->lists('id'))){
                $completed = 'bg-warning bg';
            }
            elseif ($item->isExpired()){
                $completed = 'bg-danger bg';
            }
            else{$completed = 'bg-white bg';}

            $newData = [
                'id'          => $item->id,
                'submitted'   => 0,
                'start'       => \Carbon::parse($item->start,'UTC')->timezone($task->tz)->format('Y-m-d H:i'),
                'end'         => \Carbon::parse($item->end,'UTC')->timezone($task->tz)->format('Y-m-d H:i'),
                'title'       => $title,
                'description' => $task->description,
                'className'   => $this->getClassName($task->type) . ' ' . $completed,
                'allDay'      => $task->all_day ? true : false,
                'editable'    => false
            ];
            $out[] = $newData;
        }

        $items = \Model\NewCleaningSchedulesSubmitted::
            where('unit_id','=',$unitId)->where(function($query)use($start,$end){
                $query ->where(function($query)use($start,$end){
                    $query-> where('start','>=', $start)-> where('start','<=',$end);
                })->orWhere(function($query){
                    $query-> whereNotNull('expired_dates');
                });
            })-> get();

        foreach($items as $item)
        {
            $newData = [
                'id'          => $item->id,
                'submitted'   => 1,
                'title'       => $item->title,
                'description' => $item->description,
                'className'   => ($item->completed ? 'bg-success bg' : 'bg-danger bg'),
                'allDay'      => $item->all_day ? true : false,
                'editable'    => false
            ];

            $startItem = \Carbon::parse($item->start,'UTC')->timezone($item->tz)->format('Y-m-d H:i');
            $endItem = \Carbon::parse($item->end,'UTC')->timezone($item->tz)->format('Y-m-d H:i');

            if(is_null($item->expired_dates)) {
                $out[] = $newData + ['start' => $startItem, 'end' => $endItem];
            }else{
                $dates = explode(',',$item->expired_dates);
                if(count($dates)){
                    foreach($dates as $date){
                        $dateTs = explode(':',$date);
                        $startItem = \Carbon::createFromTimestamp($dateTs[0],'UTC')->timezone($item->tz)->format('Y-m-d H:i');
                        $endItem = \Carbon::createFromTimestamp($dateTs[1],'UTC')->timezone($item->tz)->format('Y-m-d H:i');
                        if(($startItem>=$start) && ($startItem<=$end)) {
                            $out[] = $newData + ['start' => $startItem, 'end' => $endItem];
                        }
                    }
                }

            }
        }
        return \Response::json($out);
    }

    public function getComplete($id)
    {
        if(!\Request::ajax())
            return $this->redirectIfNotExist();
        $cleaning_task_item = $item = \Model\NewCleaningSchedulesItems::find($id);

        $task = $item ? $item -> task : null;
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
                return \View::make('Sections\LocalManagers\FormsManager::common.modal.create', compact('form', 'cleaning_task_item', 'breadcrumbs'));
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

        $item = \Model\NewCleaningSchedulesItems::find($id);
        $task = $item -> task;
        if(!$task || !$task -> checkAccess())
            return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.not_exist')]);

        $user = $this -> auth_user;
        $unit = $user -> unit();

        if($task->staff) {
            \Input::merge(['staff_name' => $task->staff->fullname()]);
        }
        if($task->form) {
            \Input::merge(['form_name' => $task->form->name]);
        }
        \Input::merge(['title' => $task->title]);
        \Input::merge(['description' => $task->description]);
        \Input::merge(['completed' => (\Input::get('completed') ? 1 : 0)]);
        \Input::merge(['task_item_id' => $item->id]);
        \Input::merge(['start' => $item->start]);
        \Input::merge(['end' => $item->end]);
        \Input::merge(['tz' => $task->tz]);
        \Input::merge(['all_day' => ($task->all_day=='on') ? 1 : 0 ]);

        $input = \Input::all();
        $submitted = new \Model\NewCleaningSchedulesSubmitted();
        $rules = $submitted-> rules;
        $validator = \Validator::make($input, $rules);
        if(!$validator -> fails()) {
            $submitted -> fill($input);
            $submitted -> unit_id = $unit -> id;
            $save = $submitted -> save();
            if($save) {
                \Services\FilesUploader::updateAfterCreate(['new_cleaning_schedules_items2', $user->id, $unit->id, $submitted->id]);

                $item->delete();
                if($ot = $item->outstandingTask ){
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










    public function getTasksList(){
        $breadcrumbs = $this -> breadcrumbs -> addLast( 'Tasks list', false );
        return \View::make($this->regView('tasks_list'), compact('breadcrumbs'));
    }
    public function getTasksListDatatable()
    {
        if(!\Request::ajax())
            return $this -> redirectIfNotExist();
        $unitId = \Auth::user() -> unit() -> id;
        $tasks = \Model\NewCleaningSchedulesTasks::whereUnitId($unitId)->orderBy('title','ASC')->get();
        $options = [];
        if($tasks)
            foreach ($tasks as $task)
            {
                $title = '';
                $options[] = [
                    strtotime($task -> title),
                    '<span class="font-bold">'.$task -> title.'</span>',
                    $task -> staff ? $task -> staff -> fullname() : 'N/A',
                    $task -> form ? $task -> form -> name : 'N/A',
                    \Carbon::parse($task -> start, 'UTC')->timezone(\Auth::user()->timezone)->format('d/m/Y'),
                    ($task -> repeat == 'none' ? 'N/A' : \Carbon::parse($task -> repeat_to, 'UTC')->timezone(\Auth::user()->timezone)->format('d/m/Y')),
                    '<div class="uk-text-center">'.\HTML::mdOwnNumStatus($task -> items -> count()).'</div>',
                    HTML::mdOwnOuterBuilder(
                        \HTML::mdOwnButton($task->id.'/'.$task,'new-cleaning-schedule','edit','edit')
                    ),
                ];
            }
        return \Response::json(['aaData' => $options]);
    }



    public function postEdit($id)
    {
        if(!\Request::ajax())
            return $this->redirectIfNotExist();
        $task = \Model\NewCleaningSchedulesTasks::find($id);
        $rules = $this->getIndividualRules();
        $rules = array_merge($rules, $task->rules);
        unset($rules['start'],$rules['end'],$rules['all_day'],$rules['repeat']);
        $input    = \Input::except('all_day');
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

    public function getDisplay($id)
    {
        if(!\Request::ajax())
            return $this->redirectIfNotExist();
        $item = \Model\NewCleaningSchedulesItems::find($id);
        $task = $item->task;

        if(!$task || !$task -> checkAccess())
            return \Response::json(['type'=>'fail', 'msg'=>\Lang::get('/common/messages.not_exist')]);

        return \View::make($this->regView('display'), compact('task','item'));
    }





    public function getEdit($id)
    {
        if(!\Request::ajax())
            return $this->redirectIfNotExist();
        $item = \Model\NewCleaningSchedulesItems::find($id);
        $task = $item->task;

        if(!$task || !$task -> checkAccess())
            return \Response::json(['type'=>'fail', 'msg'=>\Lang::get('/common/messages.not_exist')]);

        $assignedForms = new \Model\AssignedForms();
        $genericForms  = $assignedForms -> getFormsBySelect(4,'generic');
        $unitForms     = $assignedForms -> getFormsBySelect(4,'units');
        $forms         = $genericForms  -> merge($unitForms) -> sortByDesc('id');
        $staff         = $this -> unitStaffs;
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('edit') );
        return \View::make($this->regView('edit'), compact('task','item','staff','forms','breadcrumbs'));
    }

    public function getDelete($id)
    {
        $task = \Model\NewCleaningSchedulesTasks::find($id);
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
            return \Redirect::to('/new-cleaning-schedule')->with('success', \Lang::get('/common/messages.delete_success'));
        else
            return \Redirect::to('/new-cleaning-schedule')->with('fail', \Lang::get('/common/messages.delete_fail'));
    }

    public function getForms()
    {
        $navitasForms = \Model\Forms::whereIn('id', function($query){
            $query->select('form_id')->from('assigned_forms')->whereData('generic');
        })->where('assigned_id', 4)->where('active', 1)->orderBy('id','DESC')->get();
        $unitForms = $this -> unitForms;
        $breadcrumbs = $this -> breadcrumbs -> addLast(  $this -> setAction('Forms list',false) );
        return \View::make($this->regView('cleaning_task'), compact('unitForms','navitasForms','breadcrumbs'));
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
        $submitted = \Model\NewCleaningSchedulesSubmitted::whereUnitId($unitId)->orderBy('id','desc')->get();
        $submitted = ($filter = \Input::get('datatable')) ? \Mapic::datatableFilter($filter, $submitted) : $submitted->take(100);
        $options = [];
        if($submitted)
            foreach ($submitted as $record)
            {
                $title = '<span class="font-bold">'.$record -> title.'</span>';

                if($record -> form_name)
                    $title = $title.('<BR>'.'<small class="text-muted">Form: '.$record -> form_name.'</small>');
                if($record -> staff_name)
                    $title = $title.('<BR>'.'<small class="text-muted">Staff: '.$record -> staff_name.'</small>');
                $details = $record -> form_answer_id ? 'form-details' : 'log-details';
                $options[] = [
                    strtotime($record -> created_at),
                    $record->created_at(),
                    $title,
                    $record->getSchedulesDate(),
                    ($record -> completed ? '<span class="font-bold text-success">Completed</span>' : '<span class="font-bold text-danger">Not Completed</span>'),
                    \HTML::mdOwnOuterBuilder(
                        \HTML::mdOwnButton($record->id.'/'.$details,'new-cleaning-schedule','submitted','search')
                    ),
                ];
            }
        return \Response::json(['aaData' => $options]);
    }

    public function getSubmittedDetails($id,$destin)
    {
        $this->breadcrumbs->addCrumb('/new-cleaning-schedule/submitted/', 'Submitted list');
        $breadcrumbs = $this->breadcrumbs->addLast($this->setAction('Submitted task details', false));
        $submitted = \Model\NewCleaningSchedulesSubmitted::find($id);
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
        $submitted =  \Model\NewCleaningSchedulesSubmitted::find($id);
        if($destin == 'form') {
            $submitted->formAnswer->delete();
        }
        $submitted -> delete();
        return \Redirect::to('/new-cleaning-schedule/submitted')->with('success', \Lang::get('/common/messages.delete_success'));
    }

    public function getIndividualRules()
    {
        $rules = [];

        \Input::merge(['all_day' => (\Input::get('all_day') ? 1 : 0) ]);

        if(!is_numeric(\Input::get('form_id')))
            \Input::merge(['form_id' => NULL]);

        if(!is_numeric(\Input::get('staff_id')))
            \Input::merge(['staff_id' => NULL]);

        if(!\Input::get('weekend'))
            \Input::merge(['weekend' => 0]);

        if(in_array(\Input::get('repeat'),['day','week','month']))
        {
            $rules['repeat_freq'] = 'required';
            $rules['repeat_to']   = 'required';
            if(!in_array(\Input::get('repeat_freq'),range(1, 6))){
                \Input::merge(['repeat_freq' => 1]);
            }
            $repeat_to = (\Input::get('repeat_to') . ' 23:59:59');
            $repeat_to = \Carbon::createFromFormat('Y-m-d H:i:s', $repeat_to);
            $start = \Carbon::createFromFormat('Y-m-d H:i:s', \Input::get('start'));
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

        $staffIds = \Model\NewCleaningSchedulesTasks::whereUnitId($this->auth_user->unitId())->lists('staff_id');
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
        $schedule = \Model\NewCleaningSchedulesTasks::whereUnitId($this->auth_user->unitId())->whereStaffId($staff->id);
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
            case 'default'  :return 'b-l b-2x b-warning'; ;break;
            case 'high'     :return 'b-l b-2x b-danger';  ;break;
            case 'medium'   :return 'b-l b-2x b-warning'; ;break;
            case 'low'      :return 'b-l b-2x b-success'; ;break;
            default: return 'b-l b-2x b-primary'; break;
        }
    }
}