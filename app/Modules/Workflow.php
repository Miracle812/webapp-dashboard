<?php namespace Modules;
use \Firebase\Firebase;
class Workflow extends Modules
{
    protected $options = [];
    public function __construct()
    {
        $this -> activateUserSection();
        $this -> user = \Auth::user();
        $this -> breadcrumbs = new \Services\Breadcrumbs();
        $this -> breadcrumbs -> addCrumb('workflow', 'Workflow');
        $this -> firebase = Firebase::initialize(\Config::get('services.firebase.base_url'),\Config::get('services.firebase.token'));
        \View::share('sectionName', 'Workflow');
    }

    public function getIndex()
    {
        $this->layout = \View::make($this->layout);
        $breadcrumbs = $this->breadcrumbs->addLast($this->setAction('Workflow', false));
        $this->layout->content = \View::make($this->regView('index'), compact('breadcrumbs'));

        /*
        $fb = Firebase::initialize(\Config::get('services.firebase.base_url'),\Config::get('services.firebase.token'));
        $nodeGetContent = $fb->get('');
        $fb->push('/workflow/logs', [1=>['name' => 'item on list']]);
                dd($nodeGetContent);
        use \Firebase\Firebase;
        use \Firebase\Criteria;
        use \Firebase\Auth\TokenGenerator;
        $tokenGenerator = new TokenGenerator(\Config::get('services.firebase.token'));
        $token = $tokenGenerator->generateToken(['email' => 'test@example.com']);
        $fb = Firebase::initialize(\Config::get('services.firebase.base_url'), $token,['debug' => true]);
        $nodeDeleteContent = $fb->delete('/node/path');
        $fb->set('/node/path');
        $fb->push('/node/path', [1=>['name' => 'item on list']]);
        $fb->push('/node/path', [2=>['name' => 'item on list']]);
        $fb->push('/node/path', [3=>['name' => 'item on list']]);
        $fb->push('/node/path', [4=>['name' => 'item on list']]);
        $fb->push('/node/path', [5=>['name' => 'item on list']]);
        $criteria =  new Criteria('name', ['equalTo' => 'item on list']);
        $nodeGetContent = $fb->get('/node/path', $criteria);
        dd($nodeGetContent);
        //set the content of a node
        $nodeSetContent = $fb->set('/node/path', array('data' => 'toset'));
        //update the content of a node
        $nodeUpdateContent = $fb->update('/node/path', array('data' => 'toupdate'));
        //delete a node
        //push a new item to a node
        $nodePushContent = $fb->push('/node/path', [1=>['name' => 'item on list']]);
        */
    }

    public function getDetails($id)
    {
        $item = \Model\WorkflowItems::find($id);
        $site = $item->site;
        $task = $item->task;

        switch ($task->priority) {
            case 'low' :
                $class = 'uk-badge-success';
                break;
            case 'medium' :
                $class = 'uk-badge-warning';
                break;
            case 'high' :
                $class = 'uk-badge-danger';
                break;
        }
        $date = [
            'site_id' => $item->site_id,
            'site_name' => $item->site->name,
            'item_id' => $item->id,
            'item_status' => $item->status,
            'task_id' => $task->id,
            'task_title' => $task->title,
            'task_description' => $task->description,
            'task_date' => $item->date,
            'contact_type' => $task->contact_type,
            'priority_class' => $class,
            'priority' => $task->priority
        ];
        $contacts = [];
        $users = $site->getUsersByRole('local-manager');
        $contacts[] = ['contact_fullname' => $item->site->name, 'contact_role' => 'Site', 'emails' => [['email' => $item->site->email]], 'phones' => [['number' => $item->site->phone], ['number' => $item->site->mobile_phone]]];
        foreach ($users as $user){
            $contacts[] = ['contact_fullname'=>$user->fullname(),'contact_role'=>'Local manager', 'emails'=>[['email' => $user->email]], 'phones'=>[['number' => $user->phone], ['number' => $user->mobile_phone]]];
        }
        $date['site_contacts'] = $contacts;

        $tls = [];
        $timelines = $item->timelines;

        function getIcon ($timeline)
        {
            switch($timeline->action){
                case 'status'   : $ico = 'autorenew'; break;
                case 'create'   : $ico = 'plus'; break;
                case 'delete'   : $ico = 'times'; break;
                case 'complete' : $ico = 'check'; break;
                default : $ico = ''; break;
            }
            return $ico;
        };

        function getClass ($timeline)
        {
            switch($timeline->action){
                case 'status'   : $class = ($timeline->message == 'open' ? 'timeline_icon_success' : 'timeline_icon_danger'); break;
                case 'create'   : $class = 'timeline_icon_success'; break;
                case 'delete'   : $class = 'timeline_icon_danger'; break;
                case 'complete' : $class = 'timeline_icon_success'; break;
                default : $class = ''; break;
            }
            return $class;
        };

        $tls[] = ['day'=>'00:00','month'=>\Carbon::now()->format('d').' '.\Carbon::now()->format('M'),'message'=>$task->title.' has been created by system.','class'=>'timeline_icon_success','icon'=>'add'];
        foreach($timelines as $timeline){
            $icon = getIcon($timeline);
            $class = getClass($timeline);
            $message = $this->getMessage($timeline);
            $tls[] = ['day'=>\Carbon::parse($timeline->created_at)->timezone(\Auth::user()->timezone)->format('H:i'),'month'=>\Carbon::parse($timeline->created_at)->format('d').' '.\Carbon::parse($timeline->created_at)->format('M'),'message'=>$message,'class'=>$class,'icon'=>$icon];
        }
        $date['timelines'] = $tls;
        return \Response::json($date);
    }

    public function getMessage($timeline)
    {
        $user = $timeline->user;
        $task = $timeline->task;

        switch($timeline->action){
            case 'status' :
                $status = (($timeline->message == 'progress') ? 'in ' : '').$timeline->message;
                $msg = $task->title.' status has been changed to "'.strtoupper($status).'" by '.$user->fullname().'.'; break;
            case 'create' : $msg = $task->title.' has been created by '.$user->fullname().'.'; break;
            case 'delete' : $msg = $task->title.' has been deleted by '.$user->fullname().'.'; break;
            case 'complete' : $msg = 'Task name has been completed by '.$user->fullname().'.'; break;
            default : $msg = ''; break;
        }
        return $msg;
    }

    public function datatable()
    {
        $workflowTasks = \Model\WorkflowItems::
            select([
                'workflow_items.id',
                'workflow_tasks.title',
                'units.name',
                'workflow_tasks.contact_type',
                'workflow_tasks.priority',
                'workflow_items.date',
                'workflow_items.status'
            ])->join('workflow_tasks', function($leftJoin){
                $leftJoin->on('workflow_tasks.id', '=', 'workflow_items.task_id');
            })->join('units', function($leftJoin){
                $leftJoin->on('units.id', '=', 'workflow_items.site_id');
            })->where(function($query){
                $query->where(function($query){
                    $query->whereNotIn('workflow_tasks.assigned_officers',['default']);
                    $query->whereNotNull('workflow_tasks.assigned_officers');
                    $query->whereRaw(findinsetArray([\Auth::user()->id],'workflow_tasks.assigned_officers'));
                    //$query->whereNotIn('workflow_tasks.assigned_sites',['default']);
                    //$query->whereNotNull('workflow_tasks.assigned_sites');
                    //$query->whereRaw(findinsetArray(\Auth::user()->units->lists('id'),'workflow_tasks.assigned_sites'));
                })->orWhere(function($query){
                    $query->where('workflow_tasks.assigned_sites','default');
                    $query->where('workflow_tasks.assigned_officers','default');
                    $query->whereIn('workflow_items.site_id',\Auth::user()->units->lists('id'));
                })->orWhere(function($query){
                    $query->whereNotIn('workflow_tasks.assigned_officers',['default']);
                    $query->whereNotNull('workflow_tasks.assigned_officers');
                    $query->whereRaw(findinsetArray([\Auth::user()->id],'workflow_tasks.assigned_officers'));
                    //$query->where('workflow_tasks.assigned_sites','default');
                    //$query->whereIn('workflow_items.site_id',\Auth::user()->units->lists('id'));
                })->orWhere(function($query){
                    $query->where('workflow_tasks.assigned_officers','default');
                    $query->whereIn('workflow_items.site_id',\Auth::user()->units->lists('id'));
                    $query->whereNotIn('workflow_tasks.assigned_sites',['default']);
                    $query->whereNotNull('workflow_tasks.assigned_sites');
                    $query->whereRaw(findinsetArray(\Auth::user()->units->lists('id'),'workflow_tasks.assigned_sites'));
                });
            })->where(function($query){
                $query->where(function($query){
                    $query->where('workflow_items.status','progress')->where('workflow_items.user_id',\Auth::user()->id);
                })->orWhere(function($query){
                    $query->where('workflow_items.status','open')->orWhereNull('workflow_items.status');
                });
            });

        return \Datatables::of($workflowTasks)->
            edit_column('date', function ($item)
            {
                return '<span class="uk-text-muted uk-text-small">'.$item->date.'</span>';
            })->
            edit_column('priority', function ($item) {
                switch($item->priority){
                    case 'low' :$class = 'uk-badge-success';break;
                    case 'medium' :$class = 'uk-badge-warning';break;
                    case 'high' :$class = 'uk-badge-danger';break;
                }
                return '<span class="uk-badge '.$class.'">'.strtoupper($item->priority).'</span>';
            })->
            edit_column('contact_type', function ($item) {
                return '<span clas="md-bg-amber-800"><i class="md-icon material-icons md-bg-light-green md-color-white">'.$item->contact_type.'</i></span>';
            })->
            add_column('status', function ($item)
            {
                switch($item->status){
                    case 'open' : $class = 'md-btn-success'; $text = 'Open'; break;
                    case 'progress' : $class = 'md-btn-danger'; $text = 'In Progress'; break;
                    default  : $class = 'md-btn-success'; $text = 'Open'; break;
                }
                return '<div class="uk-button-dropdown" data-uk-dropdown="{mode:\'click\',pos:\'left-center\'}">
                            <button class="md-btn md-btn-small '.$class.'">'.$text.'<i class="material-icons">&#xE313;</i></button>
                            <div class="uk-dropdown">
                                <ul class="uk-nav uk-nav-dropdown">
                                    <li><a id="change-status" href="/workflow/task/'.$item->id.'/status/open">Open</a></li>
                                    <li><a id="change-status" href="/workflow/task/'.$item->id.'/status/progress">In progress</a></li>
                                </ul>
                            </div>
                        </div>';
            })->
            add_column('details', function ($item) {
                return '<a  data-uk-tooltip="{cls:\'uk-tooltip-small\',pos:\'left\'}" title="Details task" href="#details-item-'.$item->id.'"  clas="md-bg-amber-800"><i class="md-icon material-icons '.(($item->status == 'progress')?'md-bg-light-green':'md-bg-amber-800').' md-color-white">check</i></a>';
            })->
            add_column('action', function ($item) {
                return '<a  data-uk-tooltip="{cls:\'uk-tooltip-small\',pos:\'left\'}" title="Complete task" href="#complete-item-'.$item->id.'"  clas="md-bg-amber-800"><i class="md-icon material-icons '.(($item->status == 'progress')?'md-bg-light-green':'md-bg-amber-800').' md-color-white">check</i></a>';
            })->
        remove_column('id')->
        make(false);
    }

    public function dataSites()
    {
        $datas = \Model\Headquarters::with('units')->orderBy('name')->get();
        $out = [];
        foreach($datas as $key => $client){
            if($sites = $client->units){
                $childrens = [];
                foreach($sites as $site ){
                    $childrens[] = ['id'=>$site->id,'text'=>$site->name,'iconCls'=>"md-color-amber-800 uk-icon-small  uk-icon-home"];
                }
                $out[$key] = ['text' => $client->name, 'children' => $childrens];
            }
        }
        return \Response::json($out);
    }

    public function dataOfficers()
    {
        $officers = \User::whereHas('roles',function($role){
            $role->where('name','client-relation-officer');
        })->get();

        $out = [];
        foreach($officers as $officer){
            $out[] = ['id'=>$officer->id,'text'=>$officer->fullname(),'iconCls'=>"md-color-amber-800 uk-icon-small  uk-icon-user"];
        }
        return \Response::json($out);
    }

    public function getChangeStatus($id,$status)
    {
        $item = \Model\WorkflowItems::find($id);
        if(($item->status == 'progress') && ($item->user_id !== \Auth::user()->id)) {
            return \Response::json(['type' => 'danger', 'message' => 'Sorry, The task has been already taken by ' . $item->user->fullname() . ', please take another task.']);
        }
        if($item){
            switch($status){
                case 'open':
                case 'progress':$value = $status;break;
                case 'auto':$value = ($item->status == 'progress' ? 'open' : 'progress');break;
                default :$value = 'open';break;
            }
            $item -> update(['user_id'=>\Auth::user()->id, 'status'=>$value]);
            $this -> createLog($value,'status',$item->task,$item);
        }
        return \Response::json(['type'=>'success']);
    }

    public function postCreate()
    {
        $rules = [
            'title'=>'required',
            'description',
            'priority'=>'required',
            'contact_type'=>'required',
            'assigned_sites'=>'required',
            'assigned_officers'=>'required',
            'repeat'=>'required',
            'frequency'=>'required',
            'weekend'
        ];

        if(\Input::get('assigned_officers_type')=='default'){
            unset($rules['assigned_officers']);
            \Input::merge(['assigned_officers'=>'default']);
        }else{
            if(\Input::get('assigned_officers')) {
                $assignedOfficers = array_filter(\Input::get('assigned_officers'));
                $assignedOfficers = count($assignedOfficers) ? $assignedOfficers : [];
                \Input::merge(['assigned_officers' => implode(',', $assignedOfficers)]);
            }
        }

        if(\Input::get('assigned_sites_type')=='default'){
            unset($rules['assigned_sites']);
            \Input::merge(['assigned_sites'=>'default']);
        }else{
            if(\Input::get('assigned_sites')) {
                $assignedSites = array_filter(\Input::get('assigned_sites'));
                $assignedSites = count($assignedSites) ? $assignedSites : [];
                \Input::merge(['assigned_sites' => implode(',', $assignedSites)]);
            }
        }

        $validator = \Validator::make(\Input::all(), $rules);

        $errors = $validator -> messages() -> toArray();

        if(empty($errors) )
        {
            \Input::merge(['author_id'=>\Auth::user()->id,'tz'=>\Auth::user()->timezone]);
            $new = \Model\WorkflowTasks::create(\Input::get());
            \Services\Workflow::createTaskItems($new);

            $this -> createLog(NULL,'create',$new);
            return \Response::json(['type'=>'success', 'message'=>'Task "'.$new->title.'" created.']);
        }
        else
        {
            return \Response::json(['type'=>'danger', 'form_errors' => $this->ajaxErrors($errors,[])]);
        }
    }

    public function postComplete()
    {
        $rules = [
            'summary' => 'required',
            'status'  => 'required',
            'item_id' => 'required'
        ];

        $item = \Model\WorkflowItems::find(\Input::get('item_id'));
        $messages = [];
        if($item){
            if($item->status != 'progress'){
                $messages['status.required'] = 'Before complete, please take the task and set status on "in progress"';
            }
            elseif($item->user_id !== \Auth::user()->id){
                $messages['status.required'] = 'Task has been already taken by '.$item->user->fullname().', please take and complete next task.';
            }
            else{unset($rules ['status']);}
        }

        $validator = \Validator::make(\Input::all(), $rules, $messages);
        $errors = $validator -> messages() -> toArray();

        if(empty($errors) )
        {
            $item = \Model\WorkflowItems::find(\Input::get('item_id'));

            $task = $item->task;
            \Input::merge(['author_id'=>\Auth::user()->id,'tz'=>\Auth::user()->timezone]);
                $complete = [
                    'task_id'     => $task->id,
                    'title'       => $task->title,
                    'description' => $task->description,
                    'date'        => $item->date,
                    'site_id'     => $item->site->id,
                    'site'        => $item->site->name,
                    'author_id'   => $task->author_id,
                    'author'      => $task->author->fullname(),
                    'officer_id'  => \Auth::user()->id,
                    'officer'     => \Auth::user()->fullname(),
                    'summary'     => \Input::get('summary'),
                    'completed'   => 1,
                    'tz'          => $task->tz,
                ];
            $complete = \Model\WorkflowCompleted::create($complete);
            $this -> createLog(NULL,'complete',$task,$item);
            $item->delete();
            return \Response::json(['type'=>'success', 'message'=>'Task "'.$task->title.'" for site '.$complete->site.' completed.']);
        }
        else
        {
            return \Response::json(['type'=>'danger', 'form_errors' => $this->ajaxErrors($errors,[])]);
        }
    }

    public function createLog($message,$action,$task,$item=null)
    {
        $data = ['user_id'=>\Auth::user()->id,'action'=>$action,'task_id'=>$task->id,'item_id'=>($item?$item->id:NULL),'site_id'=>($item?$item->site_id:NULL),'message'=>$message?:''];
        $log = \Model\WorkflowLogs::create($data);
        $logData=$log->toArray(); unset($logData['updated_at'],$logData['task_id'],$logData['item_id'],$logData['message']);
        $logData['fullmessage'] = $this->getMessage($log);
        $this->firebase->push(\App::environment().'/workflow/logs/task/'.$task->id.'/item/'.$item->id.'/log/'.$log->id, $logData);
    }

    public function getDelete($id)
    {
        \Model\WorkflowTasks::softDelete($id);
    }
}