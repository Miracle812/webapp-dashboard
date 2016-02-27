<?php namespace Sections\Admins;

use Illuminate\Support\Facades\Redirect;

class Units extends AdminsSection
{
    public function __construct(\Model\Units $units)
    {
        $this -> units = $units;
        parent::__construct();
        $this -> breadcrumbs -> addCrumb('units', 'Sites');
    }

    public function getIndex()
    {
        $breadcrumbs = $this->breadcrumbs->addLast($this->setAction('Sites list',false));
        return \View::make($this->regView('index'), compact('breadcrumbs'));
    }

    public function getTreeJson($id=null)
    {
        if ($id) {
            $user = \User::find($id);
            $sitedIds = $user->units->lists('id');
        }
        $datas = \Model\Headquarters::with('units')->orderBy('name')->get();
        $out = [];
        foreach($datas as $key => $client){
            if($sites = $client->units){
                $childrens = [];
                foreach($sites as $site ){
                    $childrens[] = ['id'=>$site->id,'text'=>$site->name,'iconCls'=>"", 'checked'=>(isset($sitedIds)?(in_array($site->id,$sitedIds)?true:false):false)];
                }
                $out[$key] = ['id'=>'','text' => $client->name, 'children' => $childrens];
            }
        }
        return \Response::json($out);
    }

    public function getDatatable()
    {
        if (!\Request::ajax())
            return $this->redirectIfNotExist();
        $units = \Model\Units::get();
        $options = [];
        if (count($units)) {
            foreach ($units as $unit) {
                $localManagers = $unit->getUsersByRole('local-manager');
                $visitors = $unit->getUsersByRole('visitor');

                $options[] = [
                    strtotime($unit->created_at),
                    $unit->created_at(),
                    $unit->headquarter->name,
                    $unit->name,
                    \HTML::ownOuterBuilder(
                        \HTML::ownButton(null, 'users', '', 'fa-users', 'btn-default') .
                        '<span class="badge bg-success up">' . $localManagers->count() . '</span>','div','text-center',''),
                    \HTML::ownOuterBuilder(
                        \HTML::ownButton(null, 'users', '', 'fa-users', 'btn-default') .
                        '<span class="badge bg-success up">' . $visitors->count() . '</span>','div','text-center',''),
                    \HTML::ownOuterBuilder(
                        \HTML::ownButton($unit->id, 'units', 'edit/logo', 'fa-image', 'btn-default ajaxModal')
                    ),
                    \HTML::ownOuterBuilder(
                        \HTML::ownButton($unit->id, 'units', 'active', 'fa-' . ($unit->active ? 'check' : 'times'), 'btn-' . ($unit->active ? 'success' : 'default') . ' ajaxAction')
                    ),
                    \HTML::ownOuterBuilder(
                        \HTML::ownButton($unit->id, 'units', 'edit', 'fa-pencil', 'btn-primary')
                    ),
                    \HTML::ownOuterBuilder(
                        \HTML::ownButton($unit->id, 'units', 'delete', 'fa-times', 'btn-danger')
                    )
                ];
            }
        }
        return \Response::json(['aaData' => $options]);
    }

    public function getManageUnitModules($id)
    {
        $unit = \Model\Units::find($id);
        if (!$unit || !$unit->checkAccess())
            return $this->redirectIfNotExist();

        $panelRole = \Role::
        //with('perms')
        with(['perms' => function ($query) {
            $query->whereDisabled(0);
        }])
            ->find(3);

        $modulesUnit = $unit->getDisabledModules();
        $modulesHq = $unit->headquarter->getDisabledModules();

        $breadcrumbs = $this->breadcrumbs->addLast($this->setAction('manage-unit-modules'));
        return \View::make($this->regView('manage-unit-modules'), compact('breadcrumbs', 'unit', 'panelRole', 'modulesUnit', 'modulesHq'));
    }

    public function getDeleteModulesList($id)
    {
        $unit = \Model\Units::find($id);
        if (!$unit || !$unit->checkAccess())
            return $this->redirectIfNotExist();

        $options = $unit->getOption('manage_unit_modules');
        if ($options)
            $options->delete();
        return \Redirect::back()->withSuccess('List removed');
    }

    public function postManageUnitModules($id)
    {
        $unit = \Model\Units::find($id);
        if (!$unit || !$unit->checkAccess())
            return $this->redirectIfNotExist();

        \OptionsRepository::create($unit, 'manage_unit_modules', ['disabled_modules' => \Input::get('modules')]);

        return \Redirect::back()->withSuccess('Unit list modules - submitted.');
    }


    public function getCreate()
    {
        $headquarters = \Model\Headquarters::lists('name', 'id');
        $breadcrumbs = $this->breadcrumbs->addLast($this->setAction('create'));
        return \View::make($this->regView('create'), compact('breadcrumbs', 'headquarters'));
    }

    public function getEdit($id)
    {
        $unit = \Model\Units::find($id);
        if (!$unit || !$unit->checkAccess())
            return $this->redirectIfNotExist();
        $parentOptions = \Model\OptionsMenu::whereIdentifier($unit->getTable())->first();
        $breadcrumbs = $this->breadcrumbs->addLast($this->setAction('edit'));
        return \View::make($this->regView('edit'), compact('breadcrumbs', 'unit', 'parentOptions'));
    }

    public function getEditLogo($id)
    {
        if (!\Request::ajax())
            return $this->redirectIfNotExist();

        $unit = \Model\Units::find($id);
        if (!$unit || !$unit->checkAccess())
            return \Response::json(['type' => 'fail', 'msg' => \Lang::get('/common/messages.not_exist')]);

        $breadcrumbs = $this->breadcrumbs->addLast($this->setAction('edit-logo'));
        return \View::make($this->regView('modal.edit-logo'), compact('breadcrumbs', 'unit'))->render();
    }

    public function postEditLogo($id)
    {
        if (!\Request::ajax())
            return $this->redirectIfNotExist();

        $unit = \Model\Units::find($id);
        if (!$unit || !$unit->checkAccess())
            return \Response::json(['type' => 'fail', 'msg' => \Lang::get('/common/messages.not_exist')]);

        $postData = \Input::get();
        $photo = new \Services\FilesUploader('units');
        $path = $photo->getUploadPath($unit->id);
        $logo = $photo->avatarUploader($postData, $path);
        \File::delete(public_path() . $unit->logo);
        $unit->logo = $logo;
        $update = $unit->update();
        if ($update)
            return \Response::json(['type' => 'success', 'msg' => \Lang::get('/common/messages.update_success'), 'url' => $unit->logo]);
        else
            return \Response::json(['type' => 'fail', 'msg' => \Lang::get('/common/messages.update_fail')]);
    }

    public function postCreate()
    {

        $new = new \Model\Units();
        $rules = $new->rules;
        if (!in_array(\Input::get('headquarter'), \Model\Headquarters::lists('id')))
            \Input::merge(['headquarter' => '']);
        \Input::merge(['mobile_phone' => preg_replace('/\D/', '', \Input::get('mobile_phone'))]);
        $input = \Input::all();
        $rules['headquarter'] = 'required|numeric';
        $validator = \Validator::make($input, $rules);
        if (!$validator->fails()) {
            $new->fill($input);
            $new->headquarter_id = \Input::get('headquarter');
            $new->identifier = $this->getUniqueId();

            $save = $new->save();

            if ($save) {
                $areas = new \Model\TemperaturesProbesAreas();
                $areas->createUnitProbeAreas($new->id);
                \Services\AutoMessages::create($new);
            }

            $type = $save ? 'success' : 'fail';
            return \Redirect::to('/units')->with($type, \Lang::get('/common/messages.create_' . $type));
        } else {
            return \Redirect::back()->withInput()->withErrors($validator);
        }
    }

    public function postEdit($id)
    {
        $unit = \Model\Units::find($id);
        if (!$unit || !$unit->checkAccess())
            return $this->redirectIfNotExist();
        \Input::merge(['mobile_phone' => preg_replace('/\D/', '', \Input::get('mobile_phone'))]);
        $input = \Input::all();
        $rules = $unit->rules;
        $rules['email'] = 'required|email|unique:units,email,' . $unit->id;
        $validator = \Validator::make($input, $rules);
        if (!$validator->fails()) {
            $unit->fill($input);
            $update = $unit->update();

            \OptionsRepository::createByInputs($unit, $input);

            $type = $update ? 'success' : 'fail';
            return \Redirect::back()->with($type, \Lang::get('/common/messages.update_' . $type));
        } else {
            return \Redirect::back()->withInput()->withErrors($validator);
        }
    }

    public function getActive($id)
    {
        if (!\Request::ajax())
            return $this->redirectIfNotExist();

        $unit = \Model\Units::find($id);
        if (!$unit || !$unit->checkAccess())
            return $this->redirectIfNotExist();
        $unit->active = $unit->active ? 0 : 1;
        $update = $unit->update();
        $type = $update ? 'success' : 'fail';

        return \Response::json(['type' => $type, 'msg' => \Lang::get('/common/messages.update_' . $type)]);
    }

    public function getDelete($id)
    {
        $unit = \Model\Units::find($id);
        if (!$unit || !$unit->checkAccess())
            return $this->redirectIfNotExist();

        $delete = $unit->delete();
        $type = $delete ? 'success' : 'fail';
        return \Redirect::to('/units')->with($type, \Lang::get('/common/messages.delete_' . $type));
    }

    public function getSetUnitId($id = null)
    {
        if (!$id) {
            \Session::forget('unit_id');
        } else {
            $unit = \Model\Units::find($id);
            if (!$unit || !$unit->checkAccess())
                return $this->redirectIfNotExist();

            if ($this->isCorrectUnit($id))
                \Session::put('unit_id', $id);

        }
        return \Redirect::to(\URL::previous());
    }

    public function getUniqueId()
    {
        $id = rand(11111111, 99999999);
        $unit = \Model\Units::where('identifier', '=', $id)->first();
        if ($unit)
            $this->getUniqueId();
        return $id;
    }
}
