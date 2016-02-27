<?php namespace Sections\Admins;

class AutoMessages extends AdminsSection
{
    public function __construct(\Model\AutoMessages $autoMessages)
    {
        $this -> auto_messages = $autoMessages;
        parent::__construct();
        $this -> breadcrumbs -> addCrumb('auto-messages', 'Auto messages');
    }

    public function getIndex()
    {
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('Groups list',false) );
        return \View::make($this->regView('index'), compact('breadcrumbs','threads'));
    }

    public function getMsgList($id)
    {
        $group = \Model\AutoMessagesGroups::find($id);
        $messages = $group->messages->sortBy('sort');
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('Messages list',false) );
        return \View::make($this->regView('messages.list'), compact('breadcrumbs','group','messages'));
    }

    public function getMsgDelete($id)
    {
        $msg = \Model\AutoMessages::find($id);
        $msg -> delete();
        return \Redirect::to('/auto-messages/')->with('success', \Lang::get('/common/messages.delete_success'));
    }

    public function getGroupDelete($id)
    {
        $group = \Model\AutoMessagesGroups::find($id);
        $group -> messages() -> delete();
        $group -> delete();
        return \Redirect::to('/auto-messages/')->with('success', \Lang::get('/common/messages.delete_success'));
    }

    public function getMsgEdit($id)
    {
        $message = \Model\AutoMessages::find($id);
        $group = $message->group;
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('Edit group',false) );
        return \View::make($this->regView('messages.'.$message->group->target_type.'.edit'), compact('breadcrumbs','message','group'));
    }

    public function postMsgEdit($id)
    {
        if(!\Request::ajax())
            return $this->redirectIfNotExist();
        $message = \Model\AutoMessages::find($id);
        $rules = [
            'title'=>'required|max:255',
            'message'=>'required',
        ];
        $input = \Input::all();
        $validator = \Validator::make($input, $rules);
        if(!$validator -> fails()) {
            $message -> fill($input);
            $update = $message -> update();
            if($update)
                return \Response::json(['type'=>'success', 'msg'=>\Lang::get('/common/messages.update_success')]);
            else
                return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.update_fail')]);
        }
        else
        {
            $errMsg = $this->ajaxErrors($validator->messages()->toArray(),[]);
            return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.create_fail'), 'errors' => $errMsg]);
        }

    }

    public function getGroupEdit($id)
    {
        $group = \Model\AutoMessagesGroups::find($id);
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('Edit group',false) );
        return \View::make($this->regView('groups.'.$group->target_type.'.edit'), compact('breadcrumbs','group'));
    }

    public function postMsgCreate($id)
    {

        if(!\Request::ajax())
            return $this->redirectIfNotExist();
        $group = \Model\AutoMessagesGroups::find($id);
        $sort = $group->messages->count();
        $rules = [
            'title'=>'required|max:255',
            'message'=>'required',
        ];
        \Input::merge(['sort'=>($sort+1), 'group_id'=>$group->id]);
        $input = \Input::all();
        $new = new \Model\AutoMessages();
        $validator = \Validator::make($input, $rules);
        if(!$validator -> fails()) {
            $new -> fill($input);
            $save = $new -> save();
            if($save)
                return \Response::json(['type'=>'success', 'msg'=>\Lang::get('/common/messages.create_success')]);
            else
                return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.create_fail')]);
        }
        else
        {
            $errMsg = $this->ajaxErrors($validator->messages()->toArray(),[]);
            return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.create_fail'), 'errors' => $errMsg]);
        }
    }

    public function getMsgCreate($id)
    {
        $group = \Model\AutoMessagesGroups::find($id);
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('Create message',false) );
        return \View::make($this->regView('messages.'.$group->target_type.'.create'), compact('breadcrumbs','group'));
    }

    public function postGroupEdit($id)
    {
        if(!\Request::ajax())
            return $this->redirectIfNotExist();
        $rules = $this->getRules(\Input::get('target_type'));
        unset($rules['target_type']);
        $input = \Input::all();
        $group = \Model\AutoMessagesGroups::find($id);
        $validator = \Validator::make($input, $rules);
        if(!$validator -> fails()) {
            $group -> fill($input);
            $group -> weekends  = \Input::get('weekends') ? 1 : 0;
            $update = $group -> update();
            if($update)
                return \Response::json(['type'=>'success', 'msg'=>\Lang::get('/common/messages.update_success')]);
            else
                return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.update_fail')]);
        }
        else
        {
            $errMsg = $this->ajaxErrors($validator->messages()->toArray(),[]);
            return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.create_fail'), 'errors' => $errMsg]);
        }
    }

    public function getGroupsCreate()
    {
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('Create Group',false) );
        return \View::make($this->regView('groups.create'), compact('breadcrumbs'));
    }

    public function getActiveGroup($id)
    {
        if(!\Request::ajax())
            return $this->redirectIfNotExist();
        $group = \Model\AutoMessagesGroups::find($id);
        if(!$group)
            return \Response::json(['type'=>'fail', 'msg' => \Lang::get('/common/messages.not_exist')]);

        $group -> active = $group -> active ? 0 : 1;
        $update = $group -> update();
        $type = $update ? 'success' : 'fail';
        return \Response::json(['type'=>$type, 'msg'=>\Lang::get('/common/messages.update_'.$type)]);
    }

    public function getDeleteGroup($id)
    {
        $group = \Model\AutoMessagesGroups::find($id);
        if(!$group)
            return $this -> redirectIfNotExist();
        if($group->messages->count())
            $group->messages()->delete();
        $delete = $group -> delete();
        $type = $delete ? 'success' : 'fail';
        $msg  = $delete ? \Lang::get('/common/messages.delete_success') : \Lang::get('/common/messages.delete_fail');
        return \Redirect::to('/auto-messages')->with($type, $msg);
    }

    public function getRules($type){
        $rules['name'] = 'required|max:255';
        $rules['target_type'] = 'required';
        switch ($type){
            case 'creating_users' : ;
            case 'creating_units' :
                $rules['send_hour']  = 'required';
                $rules['freq_type']  = 'required';
                $rules['freq_value'] = 'required';
                break;
            case 'pods_temps' :
                \Input::merge(['freq_type'=>'amount_trigger']);
                \Input::merge(['freq_value'=>\Input::get('amount_value')]);
                \Input::merge(['send_hour'=>0]);
                $rules['amount_value'] = 'required';
                break;
        }
        \Input::merge(['on_sms'=>\Input::get('on_sms')?1:0]);
        \Input::merge(['on_email'=>\Input::get('on_email')?1:0]);
        return $rules;
    }

    public function postSortUpdate($id)
    {
        if (!\Request::ajax())
            return $this->redirectIfNotExist();
        $group = \Model\AutoMessagesGroups::find($id);
        $msgIds = $group->messages()->lists('id');

        $data = \Input::get('data');
        $explode = explode(',',$data);

        for($i=0; $i<=count($msgIds); $i++){

            $mId = isset($explode[$i]) ? $explode[($i)] : null;
            if($mId && in_array($mId,$msgIds)){
                \Model\AutoMessages::whereId($mId)->update(['sort'=>($i+1)]);
            }
        }
        return \Response::json(['type'=>'success', 'msg'=>\Lang::get('/common/messages.update_success')]);
    }

    public function postGroupsCreate()
    {
        if(!\Request::ajax())
            return $this->redirectIfNotExist();
        $rules = $this->getRules(\Input::get('target_type'));
        $input = \Input::all();
        $new = new \Model\AutoMessagesGroups();
        $validator = \Validator::make($input, $rules);
        if(!$validator -> fails()) {
            $new -> fill($input);
            $new -> weekends  = \Input::get('weekends') ? 1 : 0;
            $save = $new -> save();
            if($save)
                return \Response::json(['type'=>'success', 'msg'=>\Lang::get('/common/messages.create_success')]);
            else
                return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.create_fail')]);
        }
        else
        {
            $errMsg = $this->ajaxErrors($validator->messages()->toArray(),[]);
            return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.create_fail'), 'errors' => $errMsg]);
        }
    }

    public function getLoadFormPart($target)
    {
        switch ($target){
            case 'pods_temps' :  $view ='amount_trigger'; break;
            case 'creating_units' :
            case 'creating_users' :  $view ='creating_induce'; break;
            default : $view = null; break;
        }
        return $view ? \View::make($this->regView('groups.form-partials.'.$view))->render() : '' ;
    }

    public function getGroupsDatatable()
    {
        if(!\Request::ajax())
            return $this->redirectIfNotExist();
        $groups = \Model\AutoMessagesGroups::get();
        $options = [];
        if (count($groups)){
            foreach ($groups as $group)
            {
                $msgCount = $group->messages->count();
                $options[] = [
                    strtotime($group->created_at),
                    $group->created_at(),
                    $group->name,
                    $group->target_type,
                    \HTML::ownIcoStatus($group->on_email),
                    \HTML::ownIcoStatus($group->on_sms),
                    \HTML::ownOuterBuilder(
                        '<a href = "'.\URL::to('/auto-messages/group/'.$group->id.'/messages').'" class="btn btn-rounded btn-sm btn-icon btn-'.($msgCount?'success':'default').' inline">'.$msgCount.'</a>'.
                        '<a href = "'.\URL::to('/auto-messages/group/'.$group->id.'/msg/create').'" class="btn btn-rounded btn-sm btn-icon btn-success inline"><i class="fa fa-plus"></i></a>'
                    ),
                    \HTML::ownOuterBuilder(
                        \HTML::ownButton('group/'.$group -> id,'auto-messages','active','fa-'.($group->active?'check':'times'),'btn-'.($group->active?'success':'default').' ajaxAction')
                    ),
                    \HTML::ownOuterBuilder(
                        \HTML::ownButton(''.$group -> id.'/edit','auto-messages','group','fa-pencil','btn-primary').
                        \HTML::ownButton('group/'.$group -> id,'auto-messages','delete','fa-times','btn-danger')
                    )
                ];
            }
        }
        return \Response::json(['aaData' => $options]);
    }
}