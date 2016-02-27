<?php namespace Sections\HqManagers;

class Units extends HqManagersSection
{

    public $section = 'units';
    public $hqId;

    public function __construct()
    {
        parent::__construct();
        $this->breadcrumbs->addCrumb('units', 'Units');
    }

    public function getIndex()
    {
        $breadcrumbs = $this->breadcrumbs->addLast($this->setAction('list'));
        return \View::make($this->regView('index'), compact('breadcrumbs'));
    }

    public function getDatatable()
    {
        if (!\Request::ajax())
            return $this->redirectIfNotExist();

        $headquarter = $this->headquarter;
        $units = $headquarter->units()->whereIn('id', $this->getUnitsId())->get();

        $options = [];
        if (count($units)) {
            foreach ($units as $unit) {
                $localManagers = $unit->getUsersByRole('local-manager');
                $visitors = $unit->getUsersByRole('visitor');

                $options[] = [
                    strtotime($unit->created_at),
                    $unit->created_at(),
                    $unit->name,
                    $unit->identifier,
                    \HTML::ownOuterBuilder(
                        \HTML::ownButton(null, 'users', '', 'fa-users', 'btn-default') .
                        '<span class="badge bg-success up">' . $localManagers->count() . '</span>'
                    ),
                    \HTML::ownOuterBuilder(
                        \HTML::ownButton(null, 'users', '', 'fa-users', 'btn-default') .
                        '<span class="badge bg-success up">' . $visitors->count() . '</span>'
                    ),
                    \HTML::ownOuterBuilder(
                        \HTML::ownButton($unit->id, 'units', 'edit/logo', 'fa-image', 'btn-default ajaxModal')
                    ),
                    \HTML::ownOuterBuilder(
                        \HTML::ownButton($unit->id, 'units', 'active', 'fa-' . ($unit->active ? 'check' : 'times'), 'btn-' . ($unit->active ? 'success' : 'default') . ' ajaxAction')
                    ),
                    \HTML::ownOuterBuilder(
                        \HTML::ownButton($unit->id, 'units', 'edit', 'fa-pencil', 'btn-primary')
                    )
                ];
            }
        }
        return \Response::json(['aaData' => $options]);
    }


    public function getEdit($id)
    {
        $unit = \Model\Units::find($id);
        if (!$unit || !$unit->checkAccess())
            return $this->redirectIfNotExist();
        $breadcrumbs = $this->breadcrumbs->addLast($this->setAction('edit'));
        return \View::make($this->regView('edit'), compact('breadcrumbs', 'unit'));
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

    public function postEdit($id)
    {
        $unit = \Model\Units::find($id);
        if (!$unit || !$unit->checkAccess())
            return $this->redirectIfNotExist();

        $input = \Input::all();
        $rules = $unit->rules;
        $rules['email'] = 'required|email|unique:units,email,' . $unit->id;
        $validator = \Validator::make($input, $rules);
        if (!$validator->fails()) {
            $unit->fill($input);
            $update = $unit->update();
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
























    public function getSetUnitId($id = null)
    {
        if(!$id){
            \Session::forget('unit_id');
        }
        else{
            $unit = \Model\Units::find($id);
            if(!$unit || !$unit->checkAccess())
                return $this -> redirectIfNotExist();

            if($this->isCorrectUnit($id))
                \Session::put('unit_id', $id);

        }
        return \Redirect::to(\URL::previous());
    }

    public function getUniqueId(){
        $id   = rand(11111111, 99999999);
        $unit = \Model\Units::where('identifier','=',$id)->first();
        if($unit)
            $this->getUniqueId();
        return $id;
    }
}