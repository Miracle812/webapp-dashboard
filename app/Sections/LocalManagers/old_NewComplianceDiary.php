<?php namespace Sections\LocalManagers;

class NewComplianceDiary extends LocalManagersSection {


    public $unitStaffs;

    public function __construct()
    {
        parent::__construct();
        $this -> unitStaffs = \Model\Staffs::whereUnitId(\Auth::user()->unitId())->get();
        $this -> breadcrumbs -> addCrumb('new-compliance-diary', 'Compliance diary');
    }

    public function getIndex(){
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('list') );
        return \View::make($this->regView('index'), compact('breadcrumbs'));
    }

    public function getSelectCreate()
    {
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('create') );
        return \View::make($this->regView('select-create'), compact('breadcrumbs'));
    }

    public function getCreate()
    {
        list($start,$end) = (explode(',',base64_decode(\Input::get('dates'))));
        $start  = \Carbon::parse(date('Y-m-d H:i:s', ($start/1000)), \Auth::user()->timezone);
        $end    = \Carbon::parse(date('Y-m-d H:i:s', ($end/1000)), \Auth::user()->timezone);
        $day    = (($start->format('H:i:s') == '00:00:00') && ($end->format('H:i:s') == '00:00:00')) == 'true' ? 1 : 0;
        $data   = ['s'=>$start,'e'=>$end,'d'=>$day];
        $staff         = $this -> unitStaffs;
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('create') );
        return \View::make($this->regView('create'), compact('breadcrumbs','data','staff'));
    }

    public function postCreate()
    {
        if (!\Request::ajax())
            return $this -> redirectIfNotExist();
        $rules = $this -> getIndividualRules();

        $input = \Input::all();
        $new   = new \Model\NewComplianceDiaryTasks();

        $rules = array_merge($rules,$new -> rules);
        $validator = \Validator::make($input, $rules);

        if(!$validator -> fails()) {

            $new -> fill($input);
            $new -> unit_id  = $this->auth_user->unitId();
            $new -> tz = \Auth::user()->timezone;
            $save = $new -> save();
            if($save) {
                $this->createTasksItems($input,$new);
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

    public function createTasksItems(array $inputs, \Model\NewComplianceDiaryTasks $task)
    {
        $items = $task -> items;
        if($items -> count()){
            \Model\NewComplianceDiaryItems::whereTaskId($task->id)->delete();
        }

        $config = \Config::get('sections.new_compliance_diary.expiry');
        $setDays = $config['days'];
        $setTime = explode(':',$config['time']);

        $from = $start = \Carbon::parse($inputs['start'],$task->tz);

        $end  = \Carbon::parse($inputs['end'],$task->tz);
        $endExp = clone $end;
        $expiry = $endExp -> addDays($setDays) -> addHours($setTime[0]) -> addMinutes($setTime[1]) -> addSeconds($setTime[2]);
        $repeat_to = $inputs['repeat_to'] ? \Carbon::parse($inputs['repeat_to'],$task->tz) : $end;

        $data = [
            'task_id' => $task -> id,
            'unit_id' => $task -> unit_id,
            'start' => $start,
            'end' => $end,
            'expiry' => $expiry,
        ];

        if(($start >= $from) && ($end <= $repeat_to)){
            \Model\NewComplianceDiaryItems::firstOrCreate($data);
        }
        if($inputs['repeat']=='none')
            return false;

        if(isset($inputs['repeat']) && in_array($inputs['repeat'],['day','week','month'])){
            $freq  = $inputs['repeat_freq'];
            do {
                switch ($inputs['repeat']) {
                    case 'day' :
                        $start = $start->addDays($freq);
                        $end = $end->addDays($freq);
                        break;
                    case 'week' :
                        $start = $start->addWeeks($freq);
                        $end = $end->addWeeks($freq);
                        break;
                    case 'month' :
                        $start = $start->addMonths($freq);
                        $end = $end->addMonths($freq);
                        break;
                }
                $endExp = clone $end;
                $data['start'] = $start;
                $data['end'] = $end;
                $data['expiry'] = $endExp -> addDays($setDays) -> hour($setTime[0])->minute($setTime[1])->second($setTime[2]);
                if(($start >= $from) && ($start <= $repeat_to)) {
                    if (!$start->isWeekend() || ($start->isWeekend() && $task->weekend)){
                        \Model\NewComplianceDiaryItems::create($data);
                    }
                }
            }
            while(($start >= $from) && ($start <= $repeat_to));
        }
    }

    public function getData()
    {
        $start = \Carbon::parse(\Input::get('start'));
        $end   = \Carbon::parse(\Input::get('end'));
        $unitId = $this->auth_user->unitId();
        $compilance = \Model\NewComplianceDiaryItems::
               where('unit_id','=',$unitId)
            -> where('start','>=',$start)
            -> where('start','<=',$end)
            -> get();
        $checkList = \Model\CheckListItems::
               where('unit_id','=',$unitId)
            -> where('start','>=',$start)
            -> where('start','<=',$end)
            -> get();
        $collection = $compilance->merge($checkList);
        $out = [];
        foreach($collection as $item)
        {
            $task = $item->task;

            $title = $task->title;
            if($staff = $task->staff)
                $title = $task->title . ' (' . $staff->fullname() .')';
            if($task->form_id && ($form = $task->form))
                $title = $task->title . ' (' . $form->name .')';


            if($this->ongoingTask($item)){
                $completed = 'md-bg-orange-600';
            }
            elseif ($item->isExpired()){
                $completed = 'md-bg-red-600';
            }
            else{$completed = 'md-bg-grey-600';}



            switch($item -> getTable()){
                case 'new_compliance_diary_items' :
                    $tooltip = 'Compliance diary';
                    $section = 'new-compliance-diary';
                    break;
                case 'check_list_items' :
                    $tooltip = 'Check list';
                    $section = 'check-list';
                    if ($item->isCompleted()) {
                        $completed = 'md-bg-green-600';
                    }
                    break;
                default : $tooltip = '';
                    break;
            }

            $newData = [
                'id'        => $item->id,
                'tooltip'   => $tooltip,
                'section'   => $section,
                'start'     => \Carbon::parse($item->start)->format('Y-m-d H:i:s'),
                'end'       => \Carbon::parse($item->end)->format('Y-m-d H:i:s'),
                'title'     => $title,
                'description' => $task->description,
                'className' => $this->getClassName($task->type) . ' no-border ' . $completed,
                'allDay'    => $task->all_day ? true : false,
                'editable'  => false
            ];
            $out[] = $newData;
        }
        return \Response::json($out);
    }

    public function ongoingTask($item)
    {
        return(($item->start <= \Carbon::now()) && ($item->expiry >= \Carbon::now())) ? true : false;
    }

    public function postEdit($id)
    {
        if(!\Request::ajax())
            return $this->redirectIfNotExist();
        $task = \Model\NewComplianceDiaryTasks::find($id);
        $rules = $this->getIndividualRules();
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
        $item = \Model\NewComplianceDiaryItems::find($id);
        $task = $item->task;
        if(!$task || !$task -> checkAccess())
            return \Response::json(['type'=>'fail', 'msg'=>\Lang::get('/common/messages.not_exist')]);
        $staff         = $this -> unitStaffs;
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('edit') );
        return \View::make($this->regView('edit'), compact('task','item','staff','breadcrumbs'));
    }

    public function getDelete($id)
    {
        $task = \Model\NewComplianceDiaryTasks::find($id);
        if(!$task || !$task -> checkAccess())
            return $this->redirectIfNotExist();
        $items = $task -> items();
        if($items->count()){
            $items->delete();
        }
        $delete = $task->delete();
        if ($delete)
            return \Redirect::to('/new-compliance-diary')->with('success', \Lang::get('/common/messages.delete_success'));
        else
            return \Redirect::to('/new-compliance-diary')->with('fail', \Lang::get('/common/messages.delete_fail'));
    }

    public function getIndividualRules()
    {
        $rules = [];

        \Input::merge(['all_day' => (\Input::get('all_day') ? 1 : 0) ]);

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
            $repeat_to = \Carbon::parse($repeat_to);
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