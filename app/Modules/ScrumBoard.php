<?php namespace Modules;
use \Firebase\Firebase;
class ScrumBoard extends Modules
{
    protected $options = [];
    public function __construct()
    {
        $this -> activateUserSection();
        $this -> user = \Auth::user();
        $this -> breadcrumbs = new \Services\Breadcrumbs();
        $this -> breadcrumbs -> addCrumb('scrum-board', 'Scrum board');
        $this -> firebase = Firebase::initialize(\Config::get('services.firebase.base_url'),\Config::get('services.firebase.token'));
        \View::share('sectionName', 'Scrum board');
    }

    public function getIndex()
    {
        $this->layout = \View::make($this->layout);
        $userId = \Auth::user()->id;
        $logs = \Model\ScrumBoardLogs::
            where(function($query)use($userId){
                $query->where('user_id', $userId);
            })->
            orWhere(function($query)use($userId){
                $query->whereHas('item',function($item)use($userId){
                    $item->where('scrum_board_items.user_id', $userId);
                });
            })->
            orderBy('id','DESC')->get();
        $scrumitems = $this->sortData(\Model\ScrumBoardItems::whereNull('deleted_at')->whereUserId($userId)->get());
        $breadcrumbs = $this->breadcrumbs->addLast($this->setAction('Scrum board', false));
        $this->layout->content = \View::make($this->regView('index'), compact('breadcrumbs','scrumitems','logs'));
    }

    public function postCreate()
    {
        $rules = ['title'=>'required','description'=>'required','priority'=>'required'];

        $validator = \Validator::make(\Input::all(), $rules);
        $errors = $validator -> messages() -> toArray();
        if(empty($errors) )
        {
            $all = \Model\ScrumBoardItems::all();
            $maxSort = $all->filter(function($item){
                return (($item->list == 1) && ($item->user_id == \Auth::user()->id));
            })->max('sort');
            $maxId = ($all->max('id')+1);
            $new = \Model\ScrumBoardItems::create(\Input::get()+['ident'=>'Navi-'.$maxId,'user_id'=>\Auth::user()->id,'list'=>1,'sort'=>($maxSort?$maxSort+1:1)]);
            $message = '';
            $this -> createLog($message, 'create', $new);
            $new->task_id = $new->id;

            return \Response::json(['type'=>'success', 'message'=>'Tasks created.', 'data'=>$new->toArray()]);
        }
        else
        {
            return \Response::json(['type'=>'danger', 'form_errors' => $this->ajaxErrors($errors,[])]);
        }
    }

    public function sortData($collection)
    {
        return $collection->sortBy('sort')->groupBy('list');
    }

    public function postSort()
    {
        $inputs=\Input::get();
        foreach($inputs as $input){
            if(isset($input['list']) && isset($input['tasks']) && ($count = count($input['tasks']))) {
                $tasks = $input['tasks'];
                for ($i = 0; $i <= ($count-1); $i++) {
                    $item = \Model\ScrumBoardItems::find($tasks[$i]);
                    if($item->list !== $input['list']){
                        $message = 'from "'.$item->columnName().'" to "'.$item->columnName($input['list']).'"' ;
                        $this->createLog($message, 'move', $item);
                    }
                    \Model\ScrumBoardItems::where('id', $tasks[$i])->update(['list'=>$input['list'],'sort'=>($i+1)]);
                }
            }
        }
        return \Response::json(['type'=>'success']);
    }

    public function getDelete($id)
    {
        \Model\ScrumBoardItems::softDelete($id);
    }

    public function createLog($message,$action,$item)
    {
        \Model\ScrumBoardLogs::create(['user_id'=>\Auth::user()->id,'action'=>$action,'item_id'=>$item->id,'message'=>$message]);
    }

    public function getDisplay($id)
    {
        $task = \Model\ScrumBoardItems::find($id);
        switch($task->priority){
            case 'minor' : $class = 'success'; break;
            case 'critical' : $class = 'warning'; break;
            case 'blocker' : $class = 'danger'; break;
            default :  $class = 'success'; break;
        }
        $task->assignee = $task->author->fullname();
        $task->priority_class = $class;
        $task->priority = strtoupper($task->priority);
        return \Response::json($task->toArray());
    }

}
