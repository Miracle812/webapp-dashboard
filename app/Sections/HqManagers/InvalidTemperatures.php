<?php namespace Sections\HqManagers;

class InvalidTemperatures extends HqManagersSection {

    public function __construct(){
        parent::__construct();
        $this -> breadcrumbs -> addCrumb('invalid-temperatures', 'Invalid temperatures');
    }

    public function getIndex()
    {
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('list') );
        return \View::make($this->regView('index'),compact('breadcrumbs'));
    }

    public function getDatatable()
    {
        if(!\Request::ajax())
            return $this->redirectIfNotExist();
        $repo = \App::make('TemperaturesRepository');
        $invalidTemps = $repo -> getInvalidTemperatures();
        if($invalidTemps->count()){
            foreach($invalidTemps as $key => $record){
                $record -> area_name = $repo->getTempsAreaName($record);
                $invalidTemps[$key] = $record;
            }
        }
        $options = [];
        $invalidTemps = ($filter = \Input::get('datatable')) ? \Mapic::datatableFilter($filter, $invalidTemps) : $invalidTemps -> take(100);

        if ($invalidTemps->count()){
            foreach ($invalidTemps as $invalidTemp)
            {
                $options[] = [
                    strtotime($invalidTemp->created_at),
                    $invalidTemp->created_at(),
                    $invalidTemp->area_name,
                    $invalidTemp->temperature(),
                    $invalidTemp->unit->name,
                ];
            }
        }
        return \Response::json(['aaData' => $options]);
    }
}