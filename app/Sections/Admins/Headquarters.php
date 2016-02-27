<?php namespace Sections\Admins;

class Headquarters extends AdminsSection {

    public function __construct(\Model\Headquarters $headquarters)
    {
        $this -> headquarters = $headquarters;
        parent::__construct();
        $this -> breadcrumbs -> addCrumb('headquarters', 'Clients');
    }

    public function getIndex()
    {
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('Clients',false) );
        return \View::make($this->regView('index'), compact('breadcrumbs'));
    }

    public function getDatatable()
    {
        if(!\Request::ajax())
            return $this->redirectIfNotExist();

        $headquarters = \Model\Headquarters::get();

        $options = [];
        if (count($headquarters)){
            foreach ($headquarters as $headquarter)
            {
                $units = $headquarter->units;
                $hqManagers    = $headquarter -> getUsersByRole('hq-manager');;
                $localManagers = $headquarter -> getUsersByRole('local-manager');
                $visitors      = $headquarter -> getUsersByRole('visitor');

                $options[] = [
                    strtotime($headquarter->created_at),
                    $headquarter->created_at(),
                    $headquarter->name,
                    \HTML::ownOuterBuilder(
                        \HTML::ownButton(null,'units','','fa-home','btn-default').
                        '<span class="badge bg-success up">'.$units->count().'</span>','div','text-center',''),
                    \HTML::ownOuterBuilder(
                        \HTML::ownButton(null,'users','','fa-users','btn-default').
                        '<span class="badge bg-success up">'.$hqManagers->count().'</span>','div','text-center',''),
                    \HTML::ownOuterBuilder(
                        \HTML::ownButton(null,'users','','fa-users','btn-default').
                        '<span class="badge bg-success up">'.$localManagers->count().'</span>','div','text-center',''),
                    \HTML::ownOuterBuilder(
                        \HTML::ownButton(null,'users','','fa-users','btn-default').
                        '<span class="badge bg-success up">'.$visitors->count().'</span>'
                    ),
                    \HTML::ownOuterBuilder(
                        \HTML::ownButton($headquarter -> id,'headquarters','edit/logo','fa-image','btn-default ajaxModal')
                    ),
                    \HTML::ownOuterBuilder(
                        \HTML::ownButton($headquarter -> id,'headquarters','active','fa-'.($headquarter->active?'check':'times'),'btn-'.($headquarter->active?'success':'default').' ajaxAction')
                    ),
                    \HTML::ownOuterBuilder(
                        \HTML::ownButton($headquarter -> id,'headquarters','edit','fa-pencil','btn-primary')
                    ),
                    \HTML::ownOuterBuilder(
                        \HTML::ownButton($headquarter -> id,'headquarters','delete','fa-times','btn-danger')
                    )
                ];
            }
        }
        return \Response::json(['aaData' => $options]);
    }

    public function getCreate()
    {
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('create') );
        return \View::make($this->regView('create'), compact('breadcrumbs'));
    }

    public function getEdit($id)
    {
        $unit = \Model\Headquarters::find($id);
        if(!$unit || !$unit->checkAccess())
            return $this -> redirectIfNotExist();
        $threadOpt = \Model\OptionsMenu::whereIdentifier($unit->getTable())->first();
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('edit') );
        return \View::make($this->regView('edit'), compact('breadcrumbs','unit','threadOpt'));
    }

    public function getManageUnitModules($id)
    {
        $hq = \Model\Headquarters::find($id);
        if(!$hq || !$hq->checkAccess())
            return $this -> redirectIfNotExist();

        $role = \Role::
            //with('perms')
            with([ 'perms' => function($query) {
                $query->whereDisabled(0);
            }])
            ->find(3);

        $modules = $hq->getDisabledModules();
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('manage-unit-modules') );
        return \View::make($this->regView('manage-unit-modules'), compact('breadcrumbs','hq','role','modules'));
    }

    public function postManageUnitModules($id)
    {
        $hq = \Model\Headquarters::find($id);
        if(!$hq || !$hq->checkAccess())
            return $this -> redirectIfNotExist();

        \OptionsRepository::create($hq,'manage_unit_modules',['disabled_modules'=>\Input::get('modules')]);

        return \Redirect::back()->withSuccess('Headquarter modules list - submitted.');
    }

    public function getDeleteModulesList($id)
    {
        $hq = \Model\Headquarters::find($id);
        if(!$hq || !$hq->checkAccess())
            return $this -> redirectIfNotExist();

        $options = $hq->getOption('manage_unit_modules');
        if($options)
            $options -> delete();
        return \Redirect::back()->withSuccess('List removed');
    }

    public function getEditLogo($id)
    {
        if(!\Request::ajax())
            return $this->redirectIfNotExist();

        $unit = \Model\Headquarters::find($id);
        if(!$unit || !$unit->checkAccess())
            return \Response::json(['type'=>'fail', 'msg' => \Lang::get('/common/messages.not_exist')]);

        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('edit-logo') );
        return \View::make($this->regView('modal.edit-logo'), compact('breadcrumbs','unit'))->render();
    }

    public function postEditLogo($id)
    {
        if(!\Request::ajax())
            return $this->redirectIfNotExist();

        $unit = \Model\Headquarters::find($id);
        if(!$unit || !$unit->checkAccess())
            return \Response::json(['type'=>'fail', 'msg' => \Lang::get('/common/messages.not_exist')]);

        $postData = \Input::get();
        $photo    = new \Services\FilesUploader('headquarters');
        $path     = $photo -> getUploadPath($unit -> id);
        $logo     = $photo -> avatarUploader($postData, $path);
        \File::delete(public_path().$unit -> logo);
        $unit -> logo = $logo;
        $update = $unit -> update();
        if($update)
            return \Response::json(['type'=>'success', 'msg' => \Lang::get('/common/messages.update_success'), 'url' => $unit -> logo]);
        else
            return \Response::json(['type'=>'fail', 'msg' => \Lang::get('/common/messages.update_fail')]);
    }

    public function postCreate()
    {
        \Input::merge(['mobile_phone' => preg_replace('/\D/', '', \Input::get('mobile_phone'))]);
        $input     = \Input::all();
        $new       = new \Model\Headquarters();
        $rules     = $new -> rules;
        $validator = \Validator::make($input, $rules);
        if(!$validator -> fails())
        {
            $new -> fill($input);
            $save = $new -> save();
            $type = $save ? 'success' : 'fail';
            return \Redirect::to('/headquarters')->with($type, \Lang::get('/common/messages.create_'.$type));
        }
        else
        {
            return \Redirect::back()->withInput()->withErrors($validator);
        }
    }

    public function postEdit($id)
    {
        $unit = \Model\Headquarters::find($id);
        if(!$unit || !$unit->checkAccess())
            return $this -> redirectIfNotExist();
        \Input::merge(['mobile_phone' => preg_replace('/\D/', '', \Input::get('mobile_phone'))]);
        $input     = \Input::all();
        $rules     = $unit -> rules;
        $rules['email'] = 'required|email|unique:units,email,'.$unit -> id;
        $validator = \Validator::make($input, $rules);
        if(!$validator -> fails())
        {
            $unit -> fill($input);
            $update = $unit -> update();
            \OptionsRepository::createByInputs($unit,$input);
            $type = $update ? 'success' : 'fail';
            return \Redirect::back()->with($type, \Lang::get('/common/messages.update_'.$type));
        }
        else
        {
            return \Redirect::back()->withInput()->withErrors($validator);
        }
    }

    public function getActive($id)
    {
        if(!\Request::ajax())
            return $this->redirectIfNotExist();

        $unit = \Model\Headquarters::find($id);
        if(!$unit || !$unit->checkAccess())
            return $this -> redirectIfNotExist();

        $unit -> active = $unit -> active ? 0 : 1;
        $update = $unit -> update();
        $type = $update ? 'success' : 'fail';

        return \Response::json(['type'=>$type, 'msg'=>\Lang::get('/common/messages.update_'.$type)]);
    }

    public function getDelete($id)
    {
        $unit = \Model\Headquarters::find($id);
        if(!$unit || !$unit->checkAccess())
            return $this -> redirectIfNotExist();

        $delete = $unit -> delete();
        $type   = $delete ? 'success' : 'fail';
        return \Redirect::to('/headquarters')->with($type, \Lang::get('/common/messages.delete_'.$type));
    }

    public function getUnits($id)
    {
        if(!\Request::ajax())
            return $this->redirectIfNotExist();
        $headquarter = \Model\Headquarters::find($id);
        if(!$headquarter)
            return \Response::json(['type'=>'fail', 'msg'=>\Lang::get('/common/messages.not_exist')]);

        $units = $headquarter -> units ->lists('name','id');
        $units = count($units) ? $units : ['error'=>\Lang::get('/common/messages.not_exist')];
        return \View::make($this->regView('partials.units'), compact('units'))->render();
    }
}