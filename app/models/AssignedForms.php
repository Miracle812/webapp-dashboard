<?php namespace Model;

class AssignedForms extends Models
{
    public $timestamps = false;
    protected $fillable = ['form_id', 'data'];

    public function form()
    {
        return $this -> belongsTo('\Model\Forms','form_id');
    }

    public function unserializeData()
    {
        $serialize = $this -> data;
        return (!$this->isGeneric() && !is_null($serialize)) ? unserialize($serialize) : null;
    }

    public function getUnitsIds()
    {
        $data = $this->unserializeData();
        $unitsIds = [];
        if(is_array($data) && count($data)){
            foreach($data as $hq => $units){
                if($units == 'all'){
                    $hqRow = \Model\Headquarters::find($hq);
                    if($hqRow && $hqRow -> units && $hqRow->units->count()){
                        $unitsIds = array_merge($unitsIds, $hqRow->units()->lists('id'));
                    }
                }
                else{
                    $unitsIds = array_merge($unitsIds, $units);
                }
            }
        }
        return $unitsIds;
    }

    public function getFormsByUnitId()
    {
        $formsIds = [];
        $user = \Auth::user();
        if($user->hasRole('local-manager') || $user->hasRole('visitor')) {
            $unit = $user->unit();
            $hq = $unit->headquarter;
            $assigneds = $this->whereNotNull('data')->orWhereNotIn('data',['generic'])->get();
            foreach ($assigneds as $assigned) {
                $data = $assigned->unserializeData();
                if (is_array($data) && count($data)) {
                    if(isset($data[$hq->id])){
                        if( ($data[$hq->id] == 'all') || (is_array($data[$hq->id]) && in_array($unit->id, $data[$hq->id])) )
                        {
                            $formsIds[] = $assigned->form_id;
                        }
                    }
                }
            }
        }
        return \Model\Forms::whereIn('id',$formsIds);
    }

    public function getHqIds()
    {
        $data = $this->unserializeData();
        return (is_array($data) && count($data)) ? array_keys($data) : [];
    }

    public function isGeneric()
    {
        return ($this -> data == 'generic');
    }

    public function getDescription()
    {
        $text = null;
        if($this->isGeneric()){
            $text = 'Generic';
        }else{
            $hqIds = $this -> getHqIds();
            if(count($hqIds) > 0){
                if( count($hqIds) == 1 ){
                    $hq = \Model\Headquarters::find($hqIds[0]);
                    if($hq) {
                        $text = 'HQ: ' . $hq->name . "\n";
                        $text .= '(Units: ' . count($this->getUnitsIds()) . ')';
                    }
                }
                elseif( count($hqIds) > 1 ){
                    $text = 'HQ\'s: '.count($hqIds)."\n";
                    $text .= '(Units: '.count($this->getUnitsIds()).')';
                }
            }
            else{
                $text = 'N/A';
            }
        }
        return $text;
    }

    public function getUnitsIdsByHq($hq){
        $serialize = $this->data;
        $data = unserialize($serialize);
        if(isset($data[$hq])){

        }
    }

    public function getFormsBySelect($section,$target)
    {
        $section=is_array($section)?$section:[$section];
        if($target == 'generic')
            return \Model\Forms::whereIn('id', function($query){
                $query->select('form_id')->from('assigned_forms')->whereData('generic');
            })->whereIn('assigned_id',$section)->where('active', 1)->orderBy('id','DESC')->get();
        if($target == 'units')
            return $this -> getFormsByUnitId() -> whereIn('assigned_id',$section) -> where('active', 1) -> orderBy('id','DESC') -> get();
    }
}