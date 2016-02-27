<?php namespace Sections\LocalManagers;

class Navinotes extends LocalManagersSection {

    static $ses = [
        'ident'=>'.files.identifier',
        'items'=>'.files.items',
    ];

    public function __construct(){
        parent::__construct();
        $this -> breadcrumbs -> addCrumb('navinotes', 'Navinotes');
    }

    public function getIndex()
    {
        $notes = \Model\Navinotes::where('unit_id', '=', $this -> auth_user -> unitId())
                            -> where('user_id', '=', $this -> auth_user -> id)
                            -> where('target_type', '=', 'navinotes')
                            -> orderBy('id','DESC')
                            -> get();
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('list') );
        return \View::make($this->regView('index'), compact('breadcrumbs','notes'));
    }

    public function getDatatable($id = null)
    {
        if(!\Request::ajax())
            return $this -> redirectIfNotExist();

        $user = $this -> auth_user;
        $naviNotes = \Model\Navinotes::
            where('unit_id', $user -> unitId()) ->
            where('user_id', $user -> id) ->
            where('target_type', 'navinotes') ->
            //where('start','>',\Carbon::now()) ->
            get();

        if(!$naviNotes -> count())
            return \Response::json(['aaData' => []]);
        $naviNotes = ($filter = \Input::get('datatable')) ? \Mapic::datatableFilter($filter, $naviNotes) : $naviNotes -> take(100);
        foreach ($naviNotes as $row)
        {
            $options[] = [
                strtotime($row -> created_at),
                $row->created_at(),

                $row->name,
                \Str::limit($row->description,50),

                \HTML::mdOwnOuterBuilder(
                    \HTML::mdOwnNumStatus($row -> files -> count())
                ),
                \HTML::mdOwnOuterBuilder(
                    \HTML::mdActionButton($row -> id,'navinotes','details','search', 'Search') .
                    \HTML::mdActionButton($row -> id,'navinotes','edit','edit','Edit')
                ),
                \HTML::mdOwnOuterBuilder(
                    \HTML::mdActionButton($row -> id,'navinotes','delete','clear','Delete')
                ),
            ];
        }
        return \Response::json(['aaData' => $options]);
    }

    public function getCreate()
    {
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('create') );
        return \View::make($this->regView('create'),compact('breadcrumbs'));
    }

    public function getDetails($id)
    {
        $navinote = \Model\Navinotes::find($id);
        if(!$navinote || !$navinote -> checkAccess())
            return $this -> redirectIfNotExist();

        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('details') );
        return \View::make($this->regView('details'),compact('breadcrumbs','navinote'));
    }

    public function postCreate()
    {
        $rules = [
            'start'       => 'required',
            'end'         => 'required',
            'priority'    => 'required',
            'name'        => 'required',
            'description' => 'required',
        ];

        $input = \Input::all();
        $validator = \Validator::make($input, $rules);
        if (!$validator -> fails()) {
            $user = $this -> auth_user;
            $new = new \Model\Navinotes();
            $new -> fill($input);
            $new -> unit_id = $user -> unitId();
            $new -> user_id = $user -> id;
            $new -> target_type = 'navinotes';
            $save = $new -> save();
            $type = $save ? 'success' : 'error';

            if($type == 'success') {
                \Services\CompilanceDiary::create($new);
            }
            $msg  = $save ? \Lang::get('/common/messages.create_success') : \Lang::get('/common/messages.create_fail');

            $files = \Model\Files::where('target_type', '=', 'navinotes')
                ->where('target_id', '=', 'create.'.\Auth::user()->id)
                ->where('unit_id', '=', \Auth::user()->unitId())
                ->where('user_id', '=', \Auth::user()->id)
                ->get();

            if ($files->count()) {
                $fileUpload = new \Services\FilesUploader('navinotes');
                $file_path = $fileUpload->getUploadPath($new->id, null);
                \File::move(public_path() . '/upload/' . 'navinotes' . '/create.'.\Auth::user()->id . '/', public_path() . $file_path);
                foreach ($files as $file) {
                    $file->target_id = $new->id;
                    $file->file_path = $file_path . 'files/';
                    $file->update();
                }
            }
            return \Redirect::to('/navinotes')->with($type, $msg);
        } else {
            return \Redirect::back()->withInput()->withErrors($validator);
        }
    }

    public function postCreateFiles($id = null)
    {
        if(!$id){
            $id = \Session::has('navinotes' . self::$ses['ident']) ? \Session::get('navinotes'. self::$ses['ident']) : \Str::random(8, 'numeric');

            if(!\Session::has('navinotes' . self::$ses['ident']))
                \Session::put('navinotes' . self::$ses['ident'], $id);
        }

        $file       = \Input::file('Filedata');
        $allowedExt = ['jpg', 'jpeg', 'png', 'pdf'];
        $fileExt    = $file->getClientOriginalExtension();

        if(!in_array(strtolower($fileExt), $allowedExt))
            return \Response::json(['type'=>'fail', 'msg' => \Lang::get('common/messages.file_invalid')],200);

        $photo = new \Services\FilesUploader('navinotes');
        $file_path = $photo -> getUploadPath($id,'files');
        $file_name = $photo -> Uploadify($file,$file_path,$allowedExt);
        $user       = \Auth::user();
        $gallery    = new \Model\Files();
        $gallery -> unit_id  = $user -> unit() -> id;
        $gallery -> user_id     = $user -> id;
        $gallery -> target_id   = $id;
        $gallery -> target_type = 'navinotes';
        $gallery -> file_name   = $file_name;
        $gallery -> file_path   = $file_path;
        $save = $gallery -> save();
        if($save && $file_name && \Session::has('navinotes' . self::$ses['ident']))
            \Session::push('navinotes' . self::$ses['items'], [$file_path,$file_name]);
        return 1;
    }

    public function getFiles($id = null)
    {
        $sessId = 'navinotes' . self::$ses['ident'];
        $id = $id ? : \Session::get( $sessId );
        $user   = \Auth::user();
        $files = \Model\Files::where( 'target_type', '=', 'navinotes' )
            ->where('target_id','=', $id)
            ->where('unit_id',  '=', $user -> unit() -> id)
            ->where('user_id',  '=', $user -> id)
            ->get();
        $htmlOutput = '';
        foreach($files as $file){
            $htmlOutput .= \View::make($this->regView('partials.files'), compact('file'))->render();
        }
        return $htmlOutput;
    }

    public function getDeleteFile($id=null)
    {
        if(!\Request::ajax())
            return $this->redirectIfNotExist();
        $item = \Model\Files::find($id);
        if(!$item || !$item -> checkAccess())
            return \Response::json(['type'=>'fail', 'msg' => \Lang::get('common/messages.not_exist')],200);
        $user  = \Auth::user();
        $respFail = \Response::json(['type'=>'fail', 'msg' => \Lang::get('common/messages.delete_fail')],200);
        if ($item -> unit_id == $user -> unit() -> id){
            \File::delete(public_path().$item -> file_path.$item -> file_name);
            if($item -> delete())
                return \Response::json(['type'=>'success', 'msg' => \Lang::get('common/messages.delete_success')],200);
            else
                return $respFail;
        }
        return $respFail;
    }

    public function getDelete($id)
    {
        $navinote = \Model\Navinotes::find($id);
        if(!$navinote || !$navinote -> checkAccess())
            return $this -> redirectIfNotExist();
        $note = clone($navinote);
        $delete = $navinote -> delete();

        if ($delete){
            \Services\CompilanceDiary::delete($note);
            if($files = $navinote -> files) {
                foreach ($files as $file) {
                    $this->getDeleteFile($file->id);
                }
            }
        }
        $type = $delete ? 'success' : 'fail';
        $msg  = $delete ? \Lang::get('/common/messages.delete_success') : \Lang::get('/common/messages.delete_fail');

        return \Redirect::back()->with($type, $msg);
    }

    public function getEdit($id)
    {
        $navinote = \Model\Navinotes::find($id);
        if(!$navinote || !$navinote -> checkAccess())
            return $this -> redirectIfNotExist();

        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('edit') );
        return \View::make($this->regView('edit'), compact('breadcrumbs','navinote'));
    }

    public function postEdit($id)
    {
        $navinote = \Model\Navinotes::find($id);
        if(!$navinote || !$navinote -> checkAccess())
            return $this -> redirectIfNotExist();

        $rules = [
            'start'       => 'required',
            'end'         => 'required',
            'priority'    => 'required',
            'name'        => 'required',
            'description' => 'required',
        ];

        $input = \Input::all();
        $validator = \Validator::make($input, $rules);

        if (!$validator -> fails())
        {
            $navinote -> fill($input);
            $update = $navinote -> update();
            \Services\CompilanceDiary::update($navinote);
            $type   = $update ? 'success' : 'error';
            $msg    = \Lang::get('/common/messages.update_'.$type);
            return \Redirect::to('/navinotes')->with($type, $msg);
        } else {
            return \Redirect::back()->withInput()->withErrors($validator);
        }
    }
}