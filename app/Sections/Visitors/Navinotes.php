<?php namespace Sections\Visitors;

class Navinotes extends VisitorsSection {

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
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('list') );
        return \View::make($this->regView('index'), compact('breadcrumbs'));
    }

    public function getDatatable($id = null)
    {
        if(!\Request::ajax())
            return $this -> redirectIfNotExist();

        $user = $this -> auth_user;
        $naviNotes = \Model\Navinotes::
            where('unit_id', $user -> unitId()) ->
           // where('user_id', $user -> id) ->
            where('target_type', 'navinotes') ->
            //where('start','>',\Carbon::now()) ->
            get();
        $naviNotes = ($filter = \Input::get('datatable')) ? \Mapic::datatableFilter($filter, $naviNotes) : $naviNotes -> take(100);

        if(!$naviNotes -> count())
        return \Response::json(['aaData' => []]);
        foreach ($naviNotes as $row)
        {
            $options[] = [
                strtotime($row -> created_at),
                $row->created_at(),

                $row->name,
                \Str::limit($row->description,50),

                \HTML::ownOuterBuilder(
                    \HTML::ownNumStatus($row -> files -> count())
                ),
                \HTML::ownOuterBuilder(
                    \HTML::ownButton($row -> id,'navinotes','details','fa-search')
                ),
            ];
        }
        return \Response::json(['aaData' => $options]);
    }

    public function getDetails($id)
    {
        $navinote = \Model\Navinotes::find($id);
        if(!$navinote || !$navinote -> checkAccess())
            return $this -> redirectIfNotExist();

        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('details') );
        return \View::make($this->regView('details'),compact('breadcrumbs','navinote'));
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
}