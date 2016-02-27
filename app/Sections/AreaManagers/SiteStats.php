<?php namespace Sections\AreaManagers;

class SiteStats extends AreaManagersSection {

    public $section = 'sitestats';
    public $hqId;

    public function __construct(){
        parent::__construct();
        $this -> breadcrumbs -> addCrumb('sitestats', 'SiteStats');
    }

    public function getIndex()
    {
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('list') );
        return \View::make($this->regView('index'), compact('breadcrumbs'));
    }

    public function getDatatable()
    {
        if(!\Request::ajax())
        {            
            return $this->redirectIfNotExist();
        }    

        /// TODO below

        $headquarter = $this -> headquarter;
        $units = $headquarter -> units() -> whereIn('id', $this -> getUnitsId())->get();

        $options = [];
        if (count($units)){
            foreach ($units as $unit)
            {
                $model = \Model\Scores::whereUnitId($unit->id)->get();
                $cur = \Model\Scores::whereUnitId($unit->id)->select('scores')->whereRaw('id IN (SELECT max(id) FROM scores GROUP BY unit_id)')->first();
                $max = $model->max('scores');
                $min = $model->min('scores');

                $options[] = [
                    $unit->id,
                    $unit->name,
                    $unit->street_number . ', ' . $unit->city,
                    $cur->scores,
                    $max,
                    $min
                ];

            }
        }
        return \Response::json(['aaData' => $options]);
    }

    // public function getCreate()
    // {
    //     $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('create') );
    //     return \View::make($this->regView('create'), compact('breadcrumbs'));
    // }

    // public function getEdit($id)
    // {
    //     $unit = \Model\Units::find($id);
    //     if(!$unit || !$unit->checkAccess())
    //         return $this -> redirectIfNotExist();
    //     $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('edit') );
    //     return \View::make($this->regView('edit'), compact('breadcrumbs','unit'));
    // }

    // public function getEditLogo($id)
    // {
    //     if(!\Request::ajax())
    //         return $this->redirectIfNotExist();

    //     $unit = \Model\Units::find($id);
    //     if(!$unit || !$unit->checkAccess())
    //         return \Response::json(['type'=>'fail', 'msg' => \Lang::get('/common/messages.not_exist')]);

    //     $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('edit-logo') );
    //     return \View::make($this->regView('modal.edit-logo'), compact('breadcrumbs','unit'))->render();
    // }

    // public function postEditLogo($id)
    // {
    //     if(!\Request::ajax())
    //         return $this->redirectIfNotExist();

    //     $unit = \Model\Units::find($id);
    //     if(!$unit || !$unit->checkAccess())
    //         return \Response::json(['type'=>'fail', 'msg' => \Lang::get('/common/messages.not_exist')]);

    //     $postData = \Input::get();
    //     $photo    = new \Services\FilesUploader('units');
    //     $path     = $photo -> getUploadPath($unit -> id);
    //     $logo     = $photo -> avatarUploader($postData, $path);
    //     \File::delete(public_path().$unit -> logo);
    //     $unit -> logo = $logo;
    //     $update = $unit -> update();
    //     if($update)
    //         return \Response::json(['type'=>'success', 'msg' => \Lang::get('/common/messages.update_success'), 'url' => $unit -> logo]);
    //     else
    //         return \Response::json(['type'=>'fail', 'msg' => \Lang::get('/common/messages.update_fail')]);
    // }

    // public function postCreate()
    // {
    //     $input     = \Input::all();
    //     $new       = new \Model\Units();
    //     $rules     = $new -> rules;
    //     $validator = \Validator::make($input, $rules);
    //     if(!$validator -> fails()) {
    //         $new -> fill($input);
    //         $new -> headquarter_id = $this->headquarter->id;
    //         $new -> identifier = $this->getUniqueId();

    //         $save = $new->save();

    //         if ($save) {
    //             $areas = new \Model\TemperaturesProbesAreas();
    //             $areas -> createUnitProbeAreas($new->id);
    //         }

    //         $type = $save ? 'success' : 'fail';
    //         return \Redirect::to('/units')->with($type, \Lang::get('/common/messages.create_'.$type));
    //     }
    //     else
    //     {
    //         return \Redirect::back()->withInput()->withErrors($validator);
    //     }
    // }

    // public function postEdit($id)
    // {
    //     $unit = \Model\Units::find($id);
    //     if(!$unit || !$unit->checkAccess())
    //         return $this -> redirectIfNotExist();

    //     $input     = \Input::all();
    //     $rules     = $unit -> rules;
    //     $rules['email'] = 'required|email|unique:units,email,'.$unit -> id;
    //     $validator = \Validator::make($input, $rules);
    //     if(!$validator -> fails())
    //     {
    //         $unit -> fill($input);
    //         $update = $unit -> update();
    //         $type = $update ? 'success' : 'fail';
    //         return \Redirect::back()->with($type, \Lang::get('/common/messages.update_'.$type));
    //     }
    //     else
    //     {
    //         return \Redirect::back()->withInput()->withErrors($validator);
    //     }
    // }

    // public function getActive($id)
    // {
    //     if(!\Request::ajax())
    //         return $this->redirectIfNotExist();

    //     $unit = \Model\Units::find($id);
    //     if(!$unit || !$unit->checkAccess())
    //         return $this -> redirectIfNotExist();

    //     $unit -> active = $unit -> active ? 0 : 1;
    //     $update = $unit -> update();
    //     $type = $update ? 'success' : 'fail';

    //     return \Response::json(['type'=>$type, 'msg'=>\Lang::get('/common/messages.update_'.$type)]);
    // }

    // public function getDelete($id)
    // {
    //     $unit = \Model\Units::find($id);
    //     if(!$unit || !$unit->checkAccess())
    //         return $this -> redirectIfNotExist();

    //     $delete = $unit -> delete();
    //     $type   = $delete ? 'success' : 'fail';
    //     return \Redirect::to('/units')->with($type, \Lang::get('/common/messages.delete_'.$type));
    // }

























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
