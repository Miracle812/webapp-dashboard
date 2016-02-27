<?php namespace Sections\LocalManagers;

class Trainings extends LocalManagersSection {

    static $ses = [
        'ident'=>'.files.identifier',
        'items'=>'.files.items',
    ];

    public function __construct(){
        parent::__construct();
        $this -> breadcrumbs -> addCrumb('trainings', 'Training');
    }

    public function getIndex()
    {
        $staff = \Model\Staffs::where('unit_id', '=', \Auth :: user() -> unit() -> id);
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('list') );
        return \View::make($this->regView('index'), compact('staff', 'breadcrumbs'));
    }

    public function getList($id=null)
    {
        $staff = null;
        if($id){
            $staff = \Model\Staffs::find($id);
            if(!$staff || !$staff -> checkAccess()){
                return $this->redirectIfNotExist();
            }
            $this -> breadcrumbs -> addCrumb('/trainings/list/'.$staff->id, $staff->fullname());
        }
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('datatable') );
        return \View::make($this->regView('list'), compact('breadcrumbs', 'id'));
    }

    public function getEdit($id)
    {
        $training = \Model\TrainingRecords::find($id);
        if(!$training || !$training -> checkAccess()){
            return $this->redirectIfNotExist();
        }
        $staff = [];
        $staffs = \Model\Staffs::where('unit_id', '=', \Auth :: user() -> unit() -> id)->get();
        foreach($staffs as $row){
            $staff[$row->id] = $row->fullname();
        }

        $this -> breadcrumbs -> addCrumb('/trainings/edit/'.$training->id, $training->staff->fullname());
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('edit') );
        return \View::make($this->regView('edit'), compact('breadcrumbs', 'training', 'staff'));
    }

    public function getDatatable($id=null)
    {
        $trainings = \Model\TrainingRecords::where('unit_id', '=', $this -> auth_user -> unit() -> id);
        if($id)
            $trainings = $trainings -> where ('staff_id',$id);
        $trainings = $trainings->get();
        if(!$trainings->count())
            return \Response::json(['aaData' => []]);

        $trainings = ($filter = \Input::get('datatable')) ? \Mapic::datatableFilter($filter, $trainings) : $trainings -> take(100);
        $tz = \Auth::user()->timezone;
        foreach ($trainings as $row)
        {
            $options[] = [
                strtotime($row->created_at),
                $row -> created_at(),
                $row -> staff -> fullname(),
                $row -> name,
                \Carbon::parse($row->date_start,'UTC')->timezone($tz)->format('Y-m-d'),
                \Carbon::parse($row->date_finish,'UTC')->timezone($tz)->format('Y-m-d'),
                \Carbon::parse($row->date_refresh,'UTC')->timezone($tz)->format('Y-m-d') . ( ($row -> repository() -> isExpired()) ? \HTML::mdOwnOuterBuilder(' (Expired) ', 'span', 'text-danger') : '' ),
                \HTML::mdOwnOuterBuilder(
                    \HTML::mdActionButton($row->id, 'trainings','details','search','Details').
                    \HTML::mdActionButton($row->id, 'trainings','edit','edit','Edit').
                    \HTML::mdActionButton($row->id, 'trainings','delete','clear', 'Clear')
                )
            ];
        }

        if(isset($options))
            return \Response::json(['aaData' => $options]);
        else
            return \Response::json(['aaData' => []]);
    }

    public function getDetails($id)
    {
        $training = \Model\TrainingRecords::find($id);
        if(!$training || !$training -> checkAccess()){
            return $this->redirectIfNotExist();
        }
        $files = $training->files;
        $staff = $training->staff;

        $this -> breadcrumbs -> addCrumb('/trainings/list/'.$staff->id, $staff->fullname());
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('details') );
        return \View::make($this->regView('details'), compact('training','files','staff','breadcrumbs'));
    }

    public function getCreate()
    {
        $staff = \Model\Staffs::where('unit_id', '=', \Auth :: user() -> unit() -> id) -> get();
        $staffArr = [];
        foreach($staff as $row)
            $staffArr[$row->id] = $row->fullname();
        $staff = $staffArr;
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('create') );
        return \View::make($this->regView('create'), compact('staff', 'breadcrumbs'));
    }

    public function postDateConterver()
    {
        $tz = \Auth::user()->timezone;
        \Input::merge(['date_start'=>\Carbon::parse(\Input::get('date_start'),$tz)->timezone('UTC')]);
        \Input::merge(['date_finish'=>\Carbon::parse(\Input::get('date_finish'),$tz)->timezone('UTC')]);
        \Input::merge(['date_refresh'=>\Carbon::parse(\Input::get('date_refresh'),$tz)->timezone('UTC')]);
    }

    public function postEdit($id)
    {
        $rules = [
            'staff_id'    => 'required',
            'name'        => 'required',
            'address'     => 'required',
            'date_start'  => 'required',
            'date_finish' => 'required',
            'date_refresh'=> 'required'
        ];

        $input = \Input::all();
        $validator = \Validator::make($input, $rules);
        if (!$validator -> fails()) {
            $this->postDateConterver();
            $training = \Model\TrainingRecords::find($id);
            $training -> fill($input);
            $update = $training -> update();
            if($update) {
                $repo = $training -> repository();
                $toExpire = $repo -> toExpire();
                $training -> update(['to_expire'=>((isset($toExpire['days']) && ($toExpire['days']>0)) ? $toExpire['days'] : 0)]);
            }
            $type = $update ? 'success' : 'error';
            $msg = $update ? \Lang::get('/common/messages.update_success') : \Lang::get('/common/messages.update_fail');
            return \Redirect::to('/trainings/list')->with($type, $msg);
        } else {
            return \Redirect::back()->withInput()->withErrors($validator);
        }
    }

    public function postCreate()
    {
        $rules = [
            'staff_id'    => 'required',
            'name'        => 'required',
            'address'     => 'required',
            'date_start'  => 'required',
            'date_finish' => 'required',
            'date_refresh'=> 'required'
        ];

        $input = \Input::all();
        $validator = \Validator::make($input, $rules);
        if (!$validator -> fails()) {
            $this -> postDateConterver();
            $unitId = \Auth::user() -> unit() -> id;
            $new = new \Model\TrainingRecords();
            $new -> fill($input);
            $new -> unit_id = $unitId;
            $new -> to_expire = 0;
            $save = $new -> save();
            if($save) {
                \Services\FilesUploader::updateAfterCreate(['training_records', \Auth::user()->id, $unitId, $new->id]);
                $repo = $new -> repository();
                $toExpire = $repo -> toExpire();
                $new -> update(['to_expire'=>((isset($toExpire['days']) && ($toExpire['days']>0)) ? $toExpire['days'] : 0)]);
            }
            $type = $save ? 'success' : 'error';
            $msg = $save ? \Lang::get('/common/messages.create_success') : \Lang::get('/common/messages.create_fail');
            return \Redirect::to('/trainings/list')->with($type, $msg);
        } else {
            return \Redirect::back()->withInput()->withErrors($validator);
        }
    }

    public function getTrainings($id_staff){
        $staff = \Model\Staffs::find($id_staff);
        $trainings = $staff ? $staff -> training_records() -> get() : [];
        $this -> params = [
            'staff' => $staff ? : [],
            'trainings' => $trainings
        ];
        $this -> view = 'trainings';
        return $this -> showView ();
        return \View::make($this->regView('list'), compact('breadcrumbs'));
    }

    public function getDelete($id){
        $destroy =  \Model\TrainingRecords::destroy($id);
        if ($destroy){
            $files = \Model\Files::
                  where('target_type', '=', 'trainings')
                ->where('target_id', '=', $id)
                ->where('unit_id', '=', $this->auth_user->unit()->id)
                ->get();
            if ($files) {
                foreach ($files as $file) {
                    $this->getDeleteFiles($file->id);
                }
            }
            return \Response::json(['type' => 'success', 'msg' => 'Record Deleted']);
        }
        else
            return \Response::json(['type'=>'fail', 'msg'=>'Record Not Deleted']);
    }
}