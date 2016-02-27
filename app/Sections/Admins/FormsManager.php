<?php namespace Sections\Admins;


class FormsManager extends AdminsSection {

    public $types = [
        'tab'        => 'fa-folder',
        'input'      => 'fa-arrows',
        'textarea'   => 'fa-align-justify',
        'paragraph'  => 'fa-align-left',
        'radio'      => 'fa-dot-circle-o',
        'checkbox'   => 'fa-check-square-o',
        'select'     => 'fa-caret-square-o-down',
        'multiselect'=> 'fa-level-down',
        'datepicker' => 'fa-calendar',
        'timepicker' => 'fa-calendar',
        'yes_no'     => 'fa-thumbs-o-up',
        'staff'      => 'fa-users',
        'files_upload' => 'fa-upload',
        'signature'  => 'fa-pencil',
        //'compliant'     => 'fa-check',
        'assign_staff'  => 'fa-share-alt',
        'submit_button' => 'fa-send',
    ];
    public function __construct(){
        parent::__construct();
        $this -> breadcrumbs -> addCrumb('forms-manager', 'Form manager');
        $this -> formsRepo = \App::make('FormsRepository');
    }

    public function getIndex()
    {
        $types = $this->types;
        $form = \Model\Forms::find(1);
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('list') );
        return \View::make($this->regView('index'), compact('types', 'breadcrumbs', 'form'));
    }

    public function getDatatable()
    {
        $options=[];
        $forms = \Model\Forms::all();
        if ($forms -> count()){
            foreach ($forms as $row)
            {
               // if($row->{$row->assigned}){
                    $options[] = [
                        strtotime($row->created_at),
                        $row->created_at(),
                        $row->name,
                        $row->assigned_id ? \Lang::get('/common/general.forms_manager.'.$this->formsRepo->assigned[$row->assigned_id]) : 'N/A, Not assigned',
                        $row->group ? $row->group->name : 'N/A',
                        '<a data-toggle="ajaxModal" href="'.\URL::to('/forms-manager/form/'.$row -> id.'/assigned').'">'.($row->assigned ? $row->assigned->getDescription() : 'N/A').'</a>',
                        \HTML::ownOuterBuilder(\HTML::ownIcoStatus($row->active)),
                        \HTML::ownOuterBuilder(
                            \HTML::ownModalButton($row -> id.'/display','forms-manager','form','fa-search','btn-default').
                            \HTML::ownButton($row -> id.'/edit','forms-manager','form','fa-pencil','btn-primary').
                            \HTML::ownButton($row -> id.'/copy','forms-manager','form','fa-copy','btn-success').
                            \HTML::ownButton('form/'.$row -> id,'forms-manager','delete','fa-times','btn-danger')
                        )
                    ];
              //  }
            }
            return \Response::json(['aaData' => $options]);
        }
        return \Response::json(['aaData' => $options]);
    }

    public function getAssigned($id)
    {
        $form = \Model\Forms::find($id);
        $hqs = \Model\Headquarters::whereActive(1)->get();
        $this -> setAction('assigned');
        return \View::make($this->regView('modal.assigned'), compact('hqs', 'form'));
    }

    public function getCopyForm($id)
    {
        $parentsId = [];
        $form = \Model\Forms::find($id);
        $newForm = $form->replicate();
        $newForm -> name = 'Copy of '.$form->name;
        $newForm -> save();
        $items = $form->items;
        foreach($items as $item){
            $newItem = $item -> replicate();
            $newItem -> form_id = $newForm->id;
            $newItem -> save();
            if($item -> type == 'tab'){
                $parentsId[$item->id] = $newItem -> id;
            }
        }
        if(count($parentsId)){
            foreach($parentsId as $key => $value){
                \Model\FormsItems::whereFormId($newForm->id)->whereParentId($key)->update(['parent_id'=>$value]);
            }
        }
        return \Redirect::back();
    }

    public function postAssigned($id)
    {
        $form = \Model\Forms::find($id);
        $hqs = \Input::get('hq');
        if(\Input::get('custom_generic')=='custom')
        {
            $out = null;
            if($hqs && count($hqs)){
                foreach($hqs as $hq){
                    $units = \Input::get('units.'.$hq);
                    if($units){
                        $out[$hq] = in_array('all', $units) ? 'all' : $units;
                    }
                }
                $data =  $out && count($out) ? serialize($out) : null;
            }
            else
                $data = null;
        }
        else
            $data = 'generic';

        $assignedForm = \Model\AssignedForms::firstOrCreate(['form_id' => $form->id]);
        $assignedForm -> update(['data'=>$data]);
        return \Response::json(['type'=>'success', 'msg'=>\Lang::get('/common/messages.action_success')]);
    }

    public function getCreate($id)
    {
        $form = \Model\Forms::with('items')->find($id);
        if(!$form)
            return \Response::json(['type'=>'fail', 'msg'=>\Lang::get('/common/messages.not_exist')]);
        $this -> setAction('create');
        return \View::make($this->regView('common.modal.create'), compact('form'));
    }

    public function getDisplay($id)
    {
        $form = \Model\Forms::with('items')->find($id);
        if(!$form || !$form -> checkAccess())
            return \Response::json(['type'=>'fail', 'msg'=>\Lang::get('/common/messages.not_exist')]);
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('Please fill and submit form.',false) );
        return \View::make($this->regView('common.modal.create'), compact('breadcrumbs', 'form'));
    }

    public function getCreateForm()
    {
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('list') );
        $assigned = $this->formsRepo->assigned;
        return \View::make($this->regView('create_form'), compact('breadcrumbs', 'assigned'));
    }

    public function postCreateForm()
    {
        \Input::merge(['active'=>(\Input::get('active')?1:0)]);
        $input     = \Input::all();
        $new       = new \Model\Forms();
        $rules     = [
            'name'        => 'required|between:1,255',
            'description' => '',
            'assigned'    => 'required',
            'new_group'   => 'between:3,255',
        ];
        $validator = \Validator::make($input, $rules);
        if(!$validator -> fails())
        {
            \Input::merge(['assigned_id'=>\Input::get('assigned')]);
            $groupId = null;
            $newGroup = \Input::get('new_group');
            $idGroup = \Input::get('group');
            if(!empty($newGroup)) {
                $group = \Model\FormsGroups::create(['assigned_id' => $input['assigned'],  'name' => $newGroup]);
                $groupId = $group->id;
            }
            elseif(!empty($idGroup)){
                $group = \Model\FormsGroups::find($idGroup);
                $groupId = $group->id;
            }
            $new -> fill(\Input::all());

            if($groupId);
                $new -> group_id = $groupId;
            $save = $new -> save();

            //add based items
            //\Model\FormsItems::create(['form_id'=>$new->id, 'label'=>'Compliant Data?', 'type'=>'compliant', 'sort'=>1, 'options'=>serialize([])]);
            \Model\FormsItems::create(['form_id'=>$new->id, 'label'=>'Submit', 'type'=>'submit_button', 'sort'=>2, 'options'=>serialize([])]);
            $type = $save ? 'success' : 'fail';
            return \Redirect::to('/forms-manager/')->with($type, \Lang::get('/common/messages.create_'.$type));
        } else {
            return \Redirect::back()->withInput()->withErrors($validator);
        }
    }

    public function getForm($id)//edit
    {
        $form = \Model\Forms::find($id);
        if(!$form || !$form -> checkAccess())
            return \Response::json(['type'=>'fail', 'msg'=>\Lang::get('/common/messages.not_exist')]);

        $maxLevels = 2;
        $form = \Model\Forms::find($id);
        $tree = $form -> getTreeFromDB();
        $types = $this -> types;
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('list') );
        $assigned = $this->formsRepo->assigned;
        return \View::make($this->regView('edit_form'), compact('types','form','assigned','breadcrumbs','maxLevels','tree'));
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
        //$rules['label'] = 'required';
        $rules = [];
        switch($type){
            case 'input':
            case 'datepicker':
            case 'timepicker':
            case 'textarea':    $rules['placeholder'] = 'required';break;
            case 'radio':
            case 'checkbox':    $rules["arrangement"] = 'required';
            case 'select':
            case 'yes_no':
            case 'multiselect':
                if(isset($inputs['options']) && ($options = $inputs['options']) && is_array($options)){
                    foreach($options as $key => $value) {
                        $rules["options.$key"] = 'required';
                    }
                } else {
                    $rules["options"] = 'required';
                }
                break;
            case 'files_upload': $rules["extensions"] = 'required'; $rules["file_size"] = 'required'; break;
            case 'yes_no': $rules['color_yes'] = 'required'; $rules['color_no'] = 'required'; break;
            case 'tab': $rules['label'] = 'required'; break;
            case 'compliant': $rules["compliant_question"] = 'required'; break;
            //case 'staff':  break;
            //case 'datepicker': break;
            //case 'paragraph':  break;
            //case 'signature':  break;
        }
        return $rules;
    }

    public function getCopyItem($id)
    {
        $item = \Model\FormsItems::find($id);
        if (!$item || !$item->checkAccess())
            return \Response::json(['type' => 'fail', 'msg' => \Lang::get('/common/messages.not_exist')]);
        $newItem = $item->replicate();
        $save = $newItem->save();
        $type = $save ? 'success' : 'fail';
        return \Response::json(['type'=>$type, 'msg'=>\Lang::get('/common/messages.copy_'.$type)]);
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
            $unserialized = unserialize($item->options);
            $item -> fill (\Input::all()) ;
            $item -> required = $required;
            if($options = \Input::get('options')){
                if(($count = count($options))>0) {
                    $options = array_combine(range(1, $count), array_values($options));
                    $unserialized['records'] = $options;
                }
            }

            if($compliantQuestion = \Input::get('compliant_question')){
                $unserialized['compliant_question'] = $compliantQuestion;
            }

            if($extensions = \Input::get('extensions')){
                if(($count = count($extensions))>0){
                    $extensions = array_combine(range(1, $count), array_values($extensions));
                    $unserialized['extensions'] = $extensions;
                }
                $unserialized['file_size'] = \Input::get('file_size');
            }

            if(($no = \Input::get('color_no')) && ($yes = \Input::get('color_yes'))){
                $buttonColors = ['yes'=>$yes,'no'=>$no];
                $unserialized['buttons_colors'] = $buttonColors;
            }

            $item -> options = serialize($unserialized);
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
            'name'       => 'required|between:1,100',
            'assigned'   => 'required',
            'description'=> ''
        );
        $validator = \Validator::make(\Input::all(), $rules);
        $errors = $validator -> messages() -> toArray();
        if( empty($errors) )
        {
            \Input::merge(['assigned_id'=>\Input::get('assigned')]);
            $groupId = null;
            $newGroup = \Input::get('new_group');
            $idGroup = \Input::get('group');
            if(!empty($newGroup)) {
                $group = \Model\FormsGroups::create(['assigned_id' => \Input::get('assigned_id'), 'name' => $newGroup]);
                $groupId = $group->id;
            }
            elseif( !empty($idGroup) && $idGroup > 0 ){
                $group = \Model\FormsGroups::find($idGroup);
                $groupId = $group->id;
            }
            $form -> fill (\Input::all()) ;
                $form -> group_id = $groupId;
            $form -> update();
            return \Response::json(['type'=>'success', 'msg'=>\Lang::get('/common/messages.update_success')]);
        } else {
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
        if(in_array($type,['compliant','assign_staff','submit_button'])) {
            if($form->items()->whereType($type)->first())
                return \Response::json(['type' => 'error', 'msg' => 'Sorry, You can add only one type of based item.']);
        }

        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('add') );
        $icon = $this -> types[$type];
        return \View::make($this->regView('modal.add.'.$type), compact('form','type', 'icon', 'breadcrumbs'));
    }

    public function postAddItem($id,$type)
    {
        if(!\Request::ajax())
            return $this -> redirectIfNotExist();
        $form = \Model\Forms::find($id);
        if(!$form || !$form -> checkAccess())
            return \Response::json(['type'=>'fail', 'msg'=>\Lang::get('/common/messages.not_exist')]);

        if(in_array($type,['compliant','assign_staff','submit_button'])) {
            if($form->items()->whereType($type)->first())
                return \Response::json(['type' => 'error', 'msg' => 'Sorry, You can add only one type of based item.']);
        }

        $required = \Input::get('required') ? 1 : 0 ;
        $rules = $this->getRules($type, $inputs = \Input::all());

        $validator = \Validator::make($inputs, $rules);
        $errors = $validator -> messages() -> toArray();
        if(empty($errors))
        {
            $count = $form->items->count();
            $new = new \Model\FormsItems();
            $new -> form_id = $id;
            $new -> type = $type;
            $new -> sort = $count+1;
            $new -> fill (\Input::all()) ;
            $new -> required = $required;
            $array = [];
            if($options = \Input::get('options')){
                if(($count = count($options))>0) {
                    $options = array_combine(range(1, $count), array_values($options));
                    $array['records'] = $options;
                }
            }

            if($compliantQuestion = \Input::get('compliant_question')){
                $unserialized['compliant_question'] = $compliantQuestion;
            }

            if($extensions = \Input::get('extensions')){
                if(($count = count($extensions))>0){
                    $extensions = array_combine(range(1, $count), array_values($extensions));
                    $array['extensions'] = $extensions;
                }
                $array['file_size'] = \Input::get('file_size');
            }

            if(($no = \Input::get('color_no')) && ($yes = \Input::get('color_yes'))){
                $buttonColors = ['yes'=>$yes,'no'=>$no];
                $array['buttons_colors'] = $buttonColors;
            }

            $new -> options = serialize($array);
            $new -> save();
            return \Response::json(['type'=>'success', 'msg'=>\Lang::get('/common/messages.create_success')]);
        }
        else
        {
            return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.create_fail'), 'errors' => $this->ajaxErrors($errors,[])]);
        }
    }

    public function getRefreshItems($id)
    {
        if(!\Request::ajax())
            return $this -> redirectIfNotExist();
        $form = \Model\Forms::find($id);
        if(!$form || !$form -> checkAccess())
            return \Response::json(['type'=>'fail', 'msg'=>\Lang::get('/common/messages.not_exist')]);
        $pageItems = $form -> getTreeFromDB();
        $types = $this -> types;
        $first = $refresh = false;
        return \View::make($this->regView('partials.form-items.item'), compact('first','pageItems','types','refresh'))->render();;
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

    public function postSortUpdate($child = null, $parent = null, $depth = 0)
    {
        ++$depth;
        $sort = 0;
        $sortlist = $child ? : \Input::all();
        if(count($sortlist)){
            foreach($sortlist as $item){
                ++$sort;
                $entity = \Model\FormsItems::find($item['id']);
                if($entity){
                    $entity->sort = $sort;
                    $entity->parent_id = $depth==0 ? 0 : $parent;
                    $entity->update();
                }
                if(isset($item['children'])) {
                    $this->postSortUpdate($item['children'], $item['id'], $depth);
                }
            }
        }
        return \Response::json(['type' => 'success', 'msg' => 'Update completed']);
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
        $form = $item -> form;
        $delete = $item -> delete();
        \Model\FormsItems::whereParentId($id)->update(['parent_id'=>NULL]);
        $type = $delete ? 'success' : 'fail';
        return \Redirect::to(\URL::to('/forms-manager/form/'.$form->id))->with($type, \Lang::get('/common/messages.delete_'.$type));
    }

    public function postDeleteForm($id)
    {
        $form = \Model\Forms::find($id);
        if(!$form || !$form -> checkAccess() )
            return $this -> redirectIfNotExist();
        $delete = $form -> delete();
        $type = $delete ? 'success' : 'fail';
        return \Redirect::to(\URL::to('/forms-manager'))->with($type, \Lang::get('/common/messages.delete_'.$type));
    }

    public function getLoadGroups($assignedId,$form=null)
    {
        if ($form) {
            $form = \Model\Forms::find($form);
        }
        $assigned = $form ? $form -> group_id : NULL;
        $groups = \Model\FormsGroups::whereAssignedId($assignedId) -> lists('name','id');
        $groups = [0 => 'Don\'t set group']+$groups;
        return \Form::select('group', $groups, $assigned, ['class'=>'form-control']);
    }
}