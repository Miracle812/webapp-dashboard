<?php namespace Modules;

class CleaningSchedule extends  Modules
{
    public $unitStaffs;
    protected $options = [];

    public function __construct()
    {
        $this -> activateUserSection();
        $this -> user = \Auth::user();
        $this -> unitStaffs = \Model\Staffs::whereUnitId($this->user->unitId())->get();
        $assignedForms = new \Model\AssignedForms();
        $this -> unitForms = $assignedForms -> getFormsBySelect(4,'units');
    }

    public function getIndex()
    {
        $this->layout = \View::make($this->layout);
        $this->layout->content = \View::make($this->regView('index'));
    }

    public function getForms()
    {
        $navitasForms = \Model\Forms::whereIn('id', function($query){
            $query->select('form_id')->from('assigned_forms')->whereData('generic');
        })->where('assigned_id', 4)->where('active', 1)->orderBy('id','DESC')->get();
        $unitForms = $this -> unitForms;
        $this->layout = \View::make($this->layout);
        $this->layout->content = \View::make($this->regView('forms_list'), compact('unitForms','navitasForms'));
    }

    public function getSubmittedList()
    {
        $this->layout = \View::make($this->layout);
        $this->layout->content = \View::make($this->regView('submitted.list'));
    }

    public function getSubmittedDetails($id)
    {
        $this->layout = \View::make($this->layout);
        $submitted = \Model\CleaningSchedulesSubmitted::find($id);
        $ansId = (($submitted && $submitted -> form_answer_id) ? $submitted -> form_answer_id : null);
        $formHTml = $ansId ?
            \App::make('\Modules\FormBuilder')->getDisplay($ansId,'render')
            : '';
        $this->layout->content = \View::make($this->regView('submitted.details'), compact('submitted','formHTml'));
    }

    public function getTasksList(){
        $this->layout = \View::make($this->layout);
        $this->layout->content = \View::make($this->regView('tasks_list'));
    }

    public function getDatatable()
    {
        if(!\Request::ajax())
            return $this -> redirectIfNotExist();
        $unitId = \Auth::user() -> unit() -> id;
        $submitted = \Model\CleaningSchedulesSubmitted::whereUnitId($unitId)->orderBy('id','desc')->get();
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
                $options[] = [
                    strtotime($record -> created_at),
                    $record->created_at(),
                    $title,
                    $record->getSchedulesDate(),
                    ($record -> completed ? '<span class="uk-badge uk-badge-success">Completed</span>' : '<span class="uk-badge uk-badge-danger">Not Completed</span>'),
                    \HTML::ownOuterBuilder(
                        \HTML::mdActionButton($record->id,'cleaning-schedule','submitted','search', 'View Details')
                    ),
                ];
            }
        return \Response::json(['aaData' => $options]);
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
        return \View::make($this->regView('create'), compact('forms','staff','data'))->render();
    }

    public function postCreate()
    {
        if (!\Request::ajax())
            return $this -> redirectIfNotExist();

        $user = \Auth::user();
        \Input::merge(['unit_id'=>$user->unitId()]);
        \Input::merge(['tz'=>$user->timezone]);

        $rules = [
            'title'	=> 'required',
            //'description' => 'required',
            'start'	=> 'required',
            'end' => 'required',
            'repeat' => 'required',
            'repeat_every' => 'required|numeric|max:12',
            //'repeat_until' => 'required'
        ];
        if(!\Input::get('is_repeatable')){
            unset($rules['repeat'],$rules['repeat_every'],$rules['repeat_until'],$rules['weekends']);
            \Input::merge(['repeat'=>NULL,'repeat_every'=>NULL,'repeat_until'=>NULL,'weekends'=>NULL]);
        }
        if(\Input::get('all_day')){
            unset($rules['end']);
            \Input::merge(['end'=>\Input::get('start')]);
        }
        if(\Input::get('form_id')=='null'){
            \Input::merge(['form_id'=>NULL]);
        }
        $validator = \Validator::make(\Input::all(), $rules);
        if($validator -> fails()){
            return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.create_fail'), 'form_errors' => $this->ajaxErrors($validator->messages()->toArray(),[])]);
        }
        if(\Input::get('start')) {
            $start = \Carbon::createFromFormat('d/m/Y',\Input::get('start'), $user->timezone)->setTime(0, 0, 0);
            \Input::merge(['start'=>$start->copy()->timezone('UTC')]);
        }
        if(\Input::get('end')) {
            $end   = \Carbon::createFromFormat('d/m/Y',\Input::get('end'),   $user->timezone)->setTime(23, 59, 59);
            \Input::merge(['end'=>$end->copy()->timezone('UTC')]);
        }
        if(\Input::get('repeat_until')) {
            $repeatUntil = \Carbon::createFromFormat('d/m/Y', \Input::get('repeat_until'), $user->timezone)->setTime(23, 59, 59);
            \Input::merge(['repeat_until' => $repeatUntil->copy()->timezone('UTC')]);
        } else {
            \Input::merge(['repeat_until' => NULL]);
        }

        $errCustom = [];
        if((\Carbon::now($user->timezone)->diffInDays($start,false) < 0)) {
            //$errCustom["start"] = ["Start date can't be less than current date."];
        }
        if(isset($end)){
            $eeod = $end->copy()->endofDay();
            if(($eeod->isPast($user->timezone))){
                //$errCustom["end"] = ["End date can't be less than current date."];
            }
        }
        if(isset($repeatUntil)){
            $reod = $repeatUntil->copy()->endofDay();
            if(($reod->isPast($user->timezone))){
                //$errCustom["repeat_until"] = ["\"Repeat until\" date can't be less than current date."];
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

        if(!(count($errCustom))) {
            $new   = new \Model\CleaningSchedulesTasks();
            $new -> fill(\Input::all());
            $save = $new -> save();
            if($save){
                \Services\CleaningSchedule::createTasksItems($new);
                return \Response::json(['type' => 'success', 'msg' => \Lang::get('/common/messages.create_success')]);
            }
            else
                return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.create_fail')]);
        }
        else
        {
            return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.create_fail'), 'form_errors' => $errCustom]);
        }
    }

    public function getData()
    {
        return \Response::json(\Services\CleaningSchedule::getCalendarData());
    }

    public function getDetails($id)
    {
        $task = \Model\CleaningSchedulesTasks::find($id);
        $utz = \Auth::user()->timezone;
        list($start,$end) = (explode(',',base64_decode(\Input::get('d'))));
        $start  = \Carbon::parse(date('Y-m-d H:i:s', ($start/1000)), $utz);
        $end    = \Carbon::parse(date('Y-m-d H:i:s', ($end/1000)), $utz);
        $this->layout = \View::make($this->layout);
        $this->layout->content = \View::make($this->regView('details'), compact('task','start','end'));
    }

    public function getComplete($id)
    {
        $this->layout = \View::make($this->layout);
        $item = \Model\CleaningSchedulesItems::find($id);
        if(!$item || !$item -> checkAccess())
            return \Redirect::to('/cleaning-schedule')->with(['errors'=>\Lang::get('/common/messages.not_exist')]);
        $task = $item -> task;
        if(!$task || !$task -> checkAccess())
            return \Redirect::to('/cleaning-schedule')->with(['errors'=>\Lang::get('/common/messages.not_exist')]);

        \Session::put('ref-url',\URL::previous());
        if($form = $task -> form){
            $submitted = $item->getLastSubmitted();
            $options = ['section' => 'cleaning_schedules_items', 'item_id'=>$id, 'form_id'=>$form->id, 'render'=>true];
            if ($submitted && ($answerId = $submitted->form_answer_id)){
                $form_render =  \App::make('\Modules\FormBuilder')->getResolve($answerId,$options);
                $this->layout->content = \View::make($this->regView('ongoing.update_form_task'), compact('form_render','answer','form','submitted','task','item'));
            } else {
                $form_render =  \App::make('\Modules\FormBuilder')->getComplete($form->id,$options);
                $this->layout->content = \View::make($this->regView('ongoing.complete_form_task'), compact('form_render','form','task','item'));
            }
        } else {
            $this->layout->content = \View::make($this->regView('ongoing.complete_simple_task'), compact('task','item'));
        }
    }

    public function postComplete($id)
    {
        $ref = \Session::get('ref-url') ? : '';
        \Session::forget('ref-url');

        if(!\Request::ajax())
            return $this->redirectIfNotExist();

        $item = \Model\CleaningSchedulesItems::find($id);
        $task = $item -> task;
        if(!$task || !$task -> checkAccess())
            return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.not_exist')]);

        $user = \Auth::user();
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
        \Input::merge(['task_id' => $task->id]);
        \Input::merge(['start' => $item->start]);
        \Input::merge(['end' => $item->end]);
        \Input::merge(['tz' => $task->tz]);
        \Input::merge(['all_day' => ($task->all_day=='on') ? 1 : 0 ]);
        $input = \Input::all();
        $submitted = new \Model\CleaningSchedulesSubmitted();
        $rules = $submitted-> rules;
        $validator = \Validator::make($input, $rules);
        if(!$validator -> fails()) {
            $submitted -> fill($input);
            $submitted -> unit_id = $unit -> id;
            $save = $submitted -> save();
            if($save) {
                \Services\FilesUploader::updateAfterCreate(['cleaning_schedules_submitted', $user->id, $unit->id, $submitted->id]);
                $item->delete();
                return \Response::json(['type'=>'success', 'redirect'=>($ref?:'/cleaning-schedule')]);

            }
            else
                return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.action_failed')]);
        }
        else
        {
            $errMsg = $this->ajaxErrors($validator->messages()->toArray(),[]);
            return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.action_failed'), 'form_errors' => $errMsg]);
        }
    }















    public function getTasksListDatatable()
    {
        if(!\Request::ajax())
            return $this -> redirectIfNotExist();
        $unitId = \Auth::user() -> unit() -> id;
        $tasks = \Model\CleaningSchedulesTasks::whereUnitId($unitId)->orderBy('title','ASC')->get();
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
                    '<div class="text-center">'.\HTML::ownNumStatus($task -> items -> count()).'</div>',
                    '<div class="text-center">'.\HTML::ownOuterBuilder(
                        \HTML::ownButton($task->id.'/'.$task,'cleaning-schedule','edit','fa-pencil')
                    ).'</div>',
                ];
            }
        return \Response::json(['aaData' => $options]);
    }

    public function postEdit($id)
    {
        if(!\Request::ajax())
            return $this->redirectIfNotExist();
        $task = \Model\CleaningSchedulesTasks::find($id);
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

    public function getEdit($id)
    {
        if(!\Request::ajax())
            return $this->redirectIfNotExist();
        $item = \Model\CleaningSchedulesItems::find($id);
        $task = $item->task;
        if(!$task || !$task -> checkAccess())
            return \Response::json(['type'=>'fail', 'msg'=>\Lang::get('/common/messages.not_exist')]);

        $assignedForms = new \Model\AssignedForms();
        $genericForms  = $assignedForms -> getFormsBySelect(4,'generic');
        $unitForms     = $assignedForms -> getFormsBySelect(4,'units');
        $forms         = $genericForms  -> merge($unitForms) -> sortByDesc('id');
        $staff         = $this -> unitStaffs;
        $breadcrumbs = $this->section->breadcrumbs->addLast( $this -> setAction('edit') );
        return \View::make($this->regView('edit'), compact('task','item','staff','forms','breadcrumbs'));
    }

    public function getDelete($id)
    {
        $task = \Model\CleaningSchedulesTasks::find($id);
        if(!$task || !$task -> checkAccess())
            return $this->redirectIfNotExist();
        $items = $task -> items();
        if($items->count()){
            $items->delete();
        }
        $delete = $task->delete();
        if ($delete)
            return \Redirect::to('/cleaning-schedule')->with('success', \Lang::get('/common/messages.delete_success'));
        else
            return \Redirect::to('/cleaning-schedule')->with('fail', \Lang::get('/common/messages.delete_fail'));
    }

    public function getSubmittedDelete($id,$destin)
    {
        $submitted =  \Model\CleaningSchedulesSubmitted::find($id);
        if($destin == 'form') {
            $submitted->formAnswer->delete();
        }
        $submitted -> delete();
        return \Redirect::to('/cleaning-schedule/submitted')->with('success', \Lang::get('/common/messages.delete_success'));
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

        $staffIds = \Model\CleaningSchedulesTasks::whereUnitId(\Auth::user()->unitId())->lists('staff_id');
        if(!$staffIds)
            return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.empty_data')]);
        $staff =  \Model\Staffs::where('unit_id','=',\Auth::user()->unitId())->whereIn('id',$staffIds)->get();
        $breadcrumbs = $this->section->breadcrumbs -> addLast( $this -> setAction('delete') );
        return \View::make($this->regView('modal.delete-by-staff'), compact('staff','breadcrumbs'));
    }

    public function postDeleteByStaff()
    {
        if(!\Request::ajax())
            return $this->redirectIfNotExist();
        $staff =  \Model\Staffs::find(\Input::get('staff_id'));
        if(!$staff || !$staff -> checkAccess())
            return $this->redirectIfNotExist();
        $schedule = \Model\CleaningSchedulesTasks::whereUnitId(\Auth::user()->unitId())->whereStaffId($staff->id);
        $delete = $schedule->count() ? $schedule -> delete() : false;
        if($delete)
            return \Response::json(['type'=>'success', 'msg'=>\Lang::get('/common/messages.delete_success')]);
        else
            return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.delete_fail')]);
    }
}