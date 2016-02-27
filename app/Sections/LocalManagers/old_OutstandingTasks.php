<?php namespace Sections\LocalManagers;

class OutstandingTasks extends LocalManagersSection {

    public function __construct(){
        parent::__construct();
        $this -> breadcrumbs -> addCrumb('outstanding-tasks', 'Outstanding Tasks');
    }

    public function getDashboardDatatable()
    {
        $tasks =  $this -> getEntity() ->
            where(function($query){
                $query ->
                where(function($query){
                    $query->where('target_type', 'temperatures_for_pods')->whereRaw('target_id IN (SELECT MAX(temp1.id) FROM temperatures_for_pods AS temp1 JOIN outstanding_task AS ot1 ON temp1.id = ot1.target_id WHERE ot1.status = 0 AND ot1.target_type = \'temperatures_for_pods\' GROUP BY temp1.area_id)');
                })->
                orWhere(function($query){
                    $query->where('target_type', 'temperatures_for_probes')->whereRaw('target_id IN (SELECT MAX(temp2.id) FROM temperatures_for_probes AS temp2 JOIN outstanding_task AS ot2 ON temp2.id = ot2.target_id WHERE ot2.status = 0 AND ot2.target_type = \'temperatures_for_probes\' GROUP BY temp2.area_id)');
                })->
                orWhere(function($query){
                    $query->whereIn('target_type', ['check_list_items','food_incidents','navinotes','temperatures_for_goods_in','training_records','new_cleaning_schedules_items','new_cleaning_schedules_items2','forms_answers']);
                });
            }) ->
            orderBy('created_at','DESC') ->
            get();

        $tasks = ($filter = \Input::get('datatable')) ? \Mapic::datatableFilter($filter, $tasks) : $tasks -> take(100);
        $options = \Services\OutstandingTasks::getDatatable($tasks);
        return \Response::json(['aaData' => $options]);
    }

    public function getResolveAll()
    {
        if(!\Request::ajax())
            return $this->redirectIfNotExist();

        $targetTypes =  $this -> getEntity() ->
            orderBy('created_at','DESC') ->
            groupBy('target_type') ->
            lists('target_type');

        if(!$targetTypes)
            return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.nothing_to_resolve')]);
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('resolve-all') );
        return \View::make($this->regView('modal.resolve-all'), compact('targetTypes','breadcrumbs')) -> render();
    }

    public function postResolveAll()
    {
        if(!\Request::ajax())
            return $this->redirectIfNotExist();

        if(!\Input::has('status')){
            \Input::merge(['status' => "0"]);
        }
        $rules = [
            'action_todo' => 'required',
            //'status'    => 'required',
        ];

        $tasks =  $this->getEntity() ->
            where('target_type', '=', \Input::get('select_section')) ->
        get();

        $input = \Input::all();
        $validator = \Validator::make($input, $rules);
        $errors = $validator->messages()->toArray();
        if(!$errors)
        {
            foreach($tasks as $item){
                $item -> fill($input);
                $update = $item -> update();
                \Services\OutstandingTasks::updateTarget( $target = $item -> target(), $input);
            }

            if($update)
                return \Response::json(['type'=>'success', 'msg'=>\Lang::get('/common/messages.update_success')]);
            else
                return \Response::json(['type'=>'fail', 'msg'=>\Lang::get('/common/messages.update_fail')]);
        }
        else
        {
            $errMsg = $this->ajaxErrors($errors,[]);
            return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.update_fail'), 'errors' => $errMsg]);
        }
    }

    public function getEntity()
    {
        return \Model\OutstandingTask::
            whereStatus(0)->
            where('unit_id',$this->auth_user->unitId()) ->
            where(function ($query){
                $query -> where('expiry_date', '>=', \Carbon::now())
                       -> orWhere('expiry_date','0000-00-00 00:00:00');
            });
    }

    public function getLoadInputs($section)
    {
        if(!\Request::ajax())
            return $this->redirectIfNotExist();
        $targetTypes =  $this -> getEntity() ->
            groupBy('target_type') ->
            lists('target_type');

        if(!in_array($section,$targetTypes))
            return '';

        switch ($section) {
            case 'temperatures_for_pods' :  //example
            case 'temperatures_for_probes' : $view = $section; break; //example
            default: $view = 'common'; break;
        }
        return \View::make($this->regView('partials.inputs.'.$view), compact('section','breadcrumbs')) -> render();
    }

    public function getResolve($id)
    {
        if(!\Request::ajax())
            return $this->redirectIfNotExist();

        $item = \Model\OutstandingTask::find($id);
        $target = $item ? $item -> target() : null;
        if(!$item || !$item -> checkAccess() || !$target)
            return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.not_exist')]);
        $table = $target->getTable();
        $items = [];
        switch ($table) {
            case 'temperatures_for_goods_in' : $view = 'specific.temperatures_for_goods_in'; break;
            case 'temperatures_for_pods' :
            case 'temperatures_for_probes' :
                $repo = \App::make('TemperaturesRepository');
                $items = $repo -> getInvalidByArea($target);
                $view = 'specific.temperatures'; break;
            case 'check_list_items' :
                $submitted = $target -> getLastSubmitted();
                return
                    ($submitted && $submitted->form_answer_id) ?
                        \Redirect::action('Sections\LocalManagers\FormsManager@getResolve',[$submitted->form_answer_id]) :
                        \Redirect::action('Sections\LocalManagers\CheckList@getComplete',[$target->id]);
            case 'new_cleaning_schedules_items' :
            case 'new_cleaning_schedules_items2' :
                $submitted = $target -> getLastSubmitted();
                return
                    ($submitted && $submitted->form_answer_id) ?
                        \Redirect::action('Sections\LocalManagers\FormsManager@getResolve',[$submitted->form_answer_id]) :
                        \Redirect::action('Modules\CleaningSchedule@getComplete',[$target->id]);
            break;
            case 'forms_answers' : return \Redirect::action('Sections\LocalManagers\FormsManager@getResolve',[$target->id]); break;
            default: $view = 'resolve'; break;
        }

        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('resolve') );
        return \View::make($this->regView('modal.'.$view), compact('item','items','target','breadcrumbs','sections')) -> render();
    }

    public function postResolve($id)
    {
        if(!\Request::ajax())
            return $this->redirectIfNotExist();

        $item = \Model\OutstandingTask::find($id);

        if(!$item || !$item -> checkAccess())
            return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.not_exist')]);

        $rules = [];
        if(!\Input::has('status')){
            \Input::merge(['status' => "0"]);
        }

        if(\Input::has('non-compliant-trend') && \Input::get('non-compliant-trend')=='other'){
            $rules = ['action_todo' => 'required'];
        }

        if(\Input::get('send_message') == 1){
            $rules['title'] = 'required|min:10|max:100';
            $rules['message'] = 'required|min:25';
        }
        if(in_array($item->target_type, ['temperatures_for_pods','temperatures_for_probes']))
            $rules['temperatures'] = 'required';

        $input = \Input::all();
        $validator = \Validator::make($input, $rules);
        $errors = $validator->messages()->toArray();
        if(!$errors)
        {
            if(($trendId = \Input::get('non-compliant-trend')) && ($trendId != 'other')){
                $tend = \Model\NonCompliantTrends::find($trendId);
                \Input::merge(['trends'=>$tend->name]);
            }
            $target = $item -> target();
            if( in_array($item->target_type,['temperatures_for_pods', 'temperatures_for_probes']) )
            {
                switch($item->target_type){
                    case 'temperatures_for_pods'   :
                        $areasIds = \Model\TemperaturesForPods::where('area_id',$target->area_id)->lists('id');
                        break;
                    case 'temperatures_for_probes' :
                        $areasIds = \Model\TemperaturesForProbes::where('area_id',$target->area_id)->lists('id');
                        break;
                }

                $temperaturesIds = array_intersect(\Input::get('temperatures'),$areasIds);

                $otTasks = \Model\OutstandingTask::whereStatus(0)->where('target_type',$item->target_type)->whereIn('target_id', $temperaturesIds)->get();
                foreach($otTasks as $otTask){
                    $otTask -> fill($input);
                    $otTask -> update();
                    \Services\OutstandingTasks::updateTarget($otTask -> target(), $input);
                }
            }
            else {
                $item -> fill($input);
                $update = $item -> update();
                \Services\OutstandingTasks::updateTarget($target, $input);
            }
            if((\Input::get('send_message') == 1) && ($target -> getTable() == 'temperatures_for_goods_in'))
            {
                $supplier = $target -> supplier;
                \Input::merge(['recipients'=>[$supplier->getTable().','.$supplier -> id]]);
                //\Services\Messages::createThread( \Input::all() );
            }
            return \Response::json(['type'=>'success', 'msg'=>\Lang::get('/common/messages.update_success')]);
        }
        else
        {
            $errMsg = $this->ajaxErrors($errors,[]);
            return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.update_fail'), 'errors' => $errMsg]);
        }
    }
}