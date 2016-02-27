<?php namespace Sections\LocalManagers;

class Staff extends LocalManagersSection {

    public function __construct(){
        parent::__construct();
        $this -> breadcrumbs -> addCrumb('staff', 'Staff');
    }

    public function getIndex()
    {
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('list') );
        return \View::make( $this -> regView('index'), compact('breadcrumbs') );
    }

    public function getEditGeneral($id)
    {
        $staff = \Model\Staffs::find($id);
        if(!$staff || !$staff -> checkAccess())
            return $this->redirectIfNotExist();

        $this -> breadcrumbs -> addCrumb( '/staff', $staff -> fullname(), null );
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('edit_general') );
        return \View::make( $this -> regView('edit_general'), compact('staff', 'breadcrumbs') );
    }

    public function getEditAvatar($id)
    {
        $staff = \Model\Staffs::find($id);
        if(!$staff || !$staff -> checkAccess())
            return $this->redirectIfNotExist();

        $this -> breadcrumbs -> addCrumb('/staff', $staff->fullname(), null);
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('edit_avatar') );
        return \View::make($this->regView('edit_avatar'), compact('staff','breadcrumbs'));
    }

    public function getCreate(){
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('create') );
        return \View::make($this->regView('create'), compact('breadcrumbs'));
    }

    public function getHealth($id){
        $staff = \Model\Staffs::find($id);
        if(!$staff || !$staff -> checkAccess())
            return $this->redirectIfNotExist();

        $this -> breadcrumbs -> addCrumb('/staff/health/'.$staff->id, $staff->fullname(), null);
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('staff_health') );
        return \View::make($this->regView('staff_health'), compact('staff','breadcrumbs'));
    }

    public function getTrainings($id){
        $staff = \Model\Staffs::find($id);
        if(!$staff || !$staff -> checkAccess())
            return $this->redirectIfNotExist();

        $this -> breadcrumbs -> addCrumb('/staff/trainings/'.$staff->id, $staff->fullname(), null);
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('staff_trainings') );
        return \View::make($this->regView('staff_trainings'), compact('staff','breadcrumbs'));
    }

    public function getDelete($id){
        $staff = \Model\Staffs::find($id);
        if(!$staff || !$staff -> checkAccess())
            return $this->redirectIfNotExist();
        $delete = $staff -> delete();
        if ($delete)
            \Session::flash('success', \Lang::get('/common/messages.success_deleted'));
        else
            \Session::flash('fail', \Lang::get('/common/messages.fail_deleted'));
        return \Redirect::to('/staff');
    }

    public function getImport()
    {
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('import') );
        return \View::make($this->regView('import'), compact('breadcrumbs'));
    }

    public function postImport()
    {
        $file = \Input::file('import');
        $model = new \Model\Staffs();
        $rules = $model->import['rules'];
        $columns = [$model->getTable() => $model->import['columns']];
        $import = \Services\FilesImport::import($rules, $columns, $file);
        if(isset($import['errors'])){
            return \Redirect::back()->withErrors($import['errors']);
        }
        else{
            return \Redirect::back()->with('success', \Lang::get('/common/messages.import_success'));
        }
    }

    public function postCreate(){
        $rules = [
            'first_name' => 'required|min:3',
            'surname'    => 'required|min:3',
            'role'       => 'required|min:3'
        ];
        $input = \Input::all();
        $validator = \Validator::make($input, $rules);
        if(!$validator -> fails()) {
            $unitId = \Auth::user() -> unit() -> id;
            $new = new \Model\Staffs();
            $new -> fill($input);
            $new -> unit_id  = $unitId;
            $save = $new -> save();
            $type = $save ? 'success' : 'error';
            $msg  = $save ? \Lang::get('/common/messages.create_success') : \Lang::get('/common/messages.create_fail');
            return \Redirect::to('/staff')->with($type, $msg);
        }
        else{
            return \Redirect::back()->withInput()->withErrors($validator);
        }
    }

    public function postEditGeneral($id){

        $staff = \Model\Staffs::find($id);
        if(!$staff || !$staff -> checkAccess())
            return $this->redirectIfNotExist();
        $rules = [
            'first_name' => 'required|min:3',
            'surname'    => 'required|min:3',
            'role'       => 'required|min:3'
        ];
        $input = \Input::all();
        $validator = \Validator::make($input, $rules);
        if(!$validator -> fails()) {
            $staff -> fill($input);
            $update = $staff -> update();
            if($update){
                return \Redirect::to('/staff')->with('success', \Lang::get('/common/messages.update_success'));
            }
        }
        else{
            return \Redirect::back()->withInput()->withErrors($validator);
        }
    }

    public function getDatatable()
    {
        $user = \Auth::user();
        $staff = \Model\Staffs::where('unit_id','=',$user->unit()->id)->get();
        $staff = ($filter = \Input::get('datatable')) ? \Mapic::datatableFilter($filter, $staff) : $staff -> take(100);
        $options = [];
        if($staff) {
            foreach ($staff as $row) {
                $options[] = [
                    strtotime($row->updated_at),
                    $row->updated_at(),
                    $row->fullname(),
                    $row->phone,
                    $row->role,
                    \HTML::mdOwnOuterBuilder(\HTML::mdOwnButton($row->id.'/list', 'health-questionnaires', 'staff', 'timeline', 'md-btn-action md-btn-small').'<span class="uk-badge uk-badge-notification uk-badge-danger up">'.$row->healthQuestionnaires->count().'</span>'),
                    \HTML::mdOwnOuterBuilder(\HTML::mdOwnButton($row->id, 'staff','trainings', 'book', 'md-btn-action md-btn-small').'<span class="uk-badge uk-badge-notification uk-badge-danger up">'.$row->trainingsRecords->count().'</span>'),
                    \HTML::mdOwnOuterBuilder(\HTML::mdOwnButton($row->id, 'staff','edit/avatar', 'person', 'md-btn-action md-btn-small')),
                    \HTML::mdOwnOuterBuilder(\HTML::mdActionButton($row->id, 'staff','edit/general', 'edit', 'Edit').\HTML::mdActionButton($row->id, 'staff','delete', 'delete', 'Delete'))
                ];
            };

            if ($options)
                return \Response::json(['aaData' => $options]);
            else
                return \Response::json(['aaData' => []]);
        }
        else
            return \Response::json(['aaData' => []]);
    }

    public function postEditAvatar($id){
        $avatar = '';
        $staff = \Model\Staffs::find($id);
        if(!$staff || !$staff -> checkAccess())
            return $this->redirectIfNotExist();
        $postData = \Input::get();
        $photo = new \Services\FilesUploader( 'staffs' );
        $path = $photo -> getUploadPath( $staff -> id );
        $avatar = $photo -> avatarUploader($postData, $path);
        \File::delete(public_path().$staff -> avatar);
        $staff -> avatar = $avatar;
        $staff -> update();
        return \Response::json(['url' => $avatar]);
    }

    public function getAjaxAvatarDelete($id){
        $staff = $this->getEntity($id);
    }
}