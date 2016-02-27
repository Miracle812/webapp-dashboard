<?php namespace Sections\LocalManagers;

use Illuminate\Database\Eloquent\Collection;

class Searching extends LocalManagersSection {

    public function postFind()
    {
        $phrase = \Input::get('phrase');
        if(!$phrase)
            return null;
        $unitId = $this->auth_user->unitId();
        $searchers = [
            'Staffs'=>[
                'columns' => ['first_name','surname','email','phone','role'],
                'specific' =>[['whereUnitId',$unitId]]
            ],
            'Suppliers'=>[
                'columns' => ['name','description','contact_person','email','phone','post_code','city','street_number'],
                'specific' =>[['whereUnitId',$unitId]]
            ],
            'Haccp'=>[
                'columns' => ['title','content','hazards','control','monitoring','corrective_action'],
                'specific' =>[['whereIn','target_id',[0,$unitId]]
                ]
            ],
            'Knowledges'=>[
                'columns' => ['title','content_one','content_one'],
                'specific' =>[['whereIn','target_id',[0,$unitId]]
                ]
            ],
            'Forms'=>[
                'columns' => ['name','description','assigned_id']
            ],
            'Navinotes'=>[
                'columns' => ['name','description'],
                'specific' =>[['whereUnitId',$unitId]]
            ],
        ];
        $collection = [];
        foreach ($searchers as $model => $fields){
            $queryModel = '\Model\\'.$model;
            $queryModel = new $queryModel();
            $query = $this -> getQueryLike($queryModel,$fields,$phrase);
            if($query && $query -> count()){
                $columns = array_merge(['id'],$fields['columns']);
                switch ($queryModel -> getTable()){
                    case 'staffs' : $columns = array_merge(['avatar'],$columns); break;
                    case 'suppliers' : $columns = array_merge(['logo'],$columns); break;
                }
                $collection[$queryModel -> getTable()][] = $query -> limit(3) -> get($columns);
            }
        }
        $response =  $collection ? $this -> getData($collection, $phrase) : [];
        return $this -> getHtml($response);
    }

    public function getData($data,$phrase)
    {
        $out = [];
        foreach($data as $table => $records){
            foreach($records as $collections){
                foreach($collections as $collection){
                    $dataOut = [];
                    $result = $this->searchValue($collection->toArray(), $phrase);
                    if($result){
                        switch ($table){
                            case 'knowledges' :
                            case 'haccp' : $dataOut = ['title'=>$collection->title,'content'=>$result,'url'=>'/'.$collection->getUrl('item')];
                                break;
                            case 'forms' :
                                $assigned = \App::make('FormsRepository')->assigned;
                                $dataOut = ['title'=>$collection->name,'content'=>$result,'url'=>'/forms-manager/form/'.$collection->id.'/create', 'type'=>\Lang::get('common/general.forms_manager.'.$assigned[$collection->assigned_id])];
                                break;
                            case 'navinotes' :  $dataOut = ['title'=>$collection->title,'content'=>$result,'url'=>$collection->getUrl('item')];
                                break;
                            case 'staffs' : $dataOut = ['title'=>$collection->fullname(),'content'=>$result,'url'=>'/'.$collection->getUrl('item'),'img'=>$collection->avatar()];
                                break;
                            case 'suppliers' : $dataOut = ['title'=>$collection->name,'content'=>$result,'url'=>'/'.$collection->getUrl('item'),'img'=>$collection->logo()];
                                break;
                        }
                        if($dataOut)
                            $out[$table][] = $dataOut;
                    }
                }
            }
        }
        return $out;
    }

    public function getHtml(array $data)
    {
        $html = '';
        if(count($data)){
            foreach ($data as $header => $records)
            {
                if(count($records)){
                    $html .= \View::make($this -> regView('partials.header'), compact('header')) -> render();
                    foreach ($records as $record){
                        $html .= \View::make($this -> regView('partials.'.$header), compact('record')) -> render();
                    }
                }
            }
        }
        else {
            $html .= \View::make($this -> regView('partials.no_results')) -> render();
        }
        return \View::make($this -> regView('partials.layout'), compact('html')) -> render();
    }

    public  function getQueryLike($query, $fields, $value)
    {
        $columns = $fields['columns'];
        $specific = isset($fields['specific']) ? $fields['specific'] : null;
        if($query->getTable() == 'forms'){
            $query = $query -> with(['assigned'=>function($query) {
                $formsIds = \App::make('\Model\AssignedForms')->getFormsByUnitId()->lists('id');
                $query->whereData('generic')->orWhereIn('form_id',$formsIds);
            }]);
        }
        if($specific)
            foreach($specific as $spec){
                if( count($spec) == 3 and is_array($spec[2]))
                    $query = $query -> $spec[0]($spec[1],$spec[2]);
                elseif( count($spec) == 2 ){
                    $query = $query -> $spec[0]($spec[1]);
                }
            }
        if(count($columns) > 0){
            if(count($columns) == 1)
                return $query -> where($columns[0],'LIKE',"%$value%");
            else{
                $query = $query -> where(function ($query) use($columns,$value) {
                    $query = $query -> where($columns[0], 'LIKE', "%$value%");
                    unset ($columns[0]);
                    foreach ($columns as $column)
                        $query = $query -> orWhere($column, 'LIKE', "%$value%");
                });
                return $query;
            }
        }
        return null;
    }

    public function searchValue(array $array, $search)
    {
        $iterr = new \RecursiveIteratorIterator(
            new \RecursiveArrayIterator($array),
            \RecursiveIteratorIterator::SELF_FIRST);
        foreach ($iterr as $key => $text) {
            $result = $this -> highlightWords($text,$search);
            if($result) {
                return $result;
            }
        }
        return null;
    }

    public function highlightWords($text, $word)
    {
        $word = strtolower($word);
        $found = preg_match('/(?:\S+\s*){0,5}\S*'.$word.'\S*(?:\s*\S+){0,5}/i', strip_tags($text), $result);
        if ($found) {
            $result = $result[0];
            $result = preg_replace('(\r\n|\n|\r|\t)', '', $result);
            return  preg_replace('/('.$word.')/i', '<span class="font-bold text-primary">$1</span>', $result);
        }
        return false;
    }
}