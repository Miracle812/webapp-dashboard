<?php
namespace Services;

class Scores extends \BaseController
{
    public $textStart = 'Scoring started';

    public static function resetScores()
    {
        if(\Carbon::now()->day == 1) {
            $scores = \Model\Scores::groupBy('unit_id')->get();
            foreach ($scores as $score) {
                $new = new \Model\Scores();
                $new->message = 'Scores updated';
                $new->scores = 1000;
                $new->type = 'restart';
                $new->value = 0;
                $new->unit_id = $score->unit_id;
                $new->target_id = $score->unit_id;
                $new->target_type = 'site';
                $new->save();
            }
        }
    }

    public static function scoresUpdateByTable($table)
    {
        $self = new self();
        $objects = null;

        switch ($table) {
            case 'training_records':
                $objects = \Model\TrainingRecords::where('date_refresh','<',\Carbon::now('UTC'))->get();
                break;
            case 'cleaning_schedules_items':
                $objects = \Model\CleaningSchedulesItems::
                    with(['task'=>function($query){
                        //$query->whereIn('type',['high','default']);
                    }]) ->
                    where('end','<',\Carbon::now('UTC')) ->
                    where('end','>',\Carbon::now('UTC')->startOfMonth()) ->
                    select('*', \DB::raw('count(id) as total'))->
                    groupBy('unit_id')->
                    get()->filter(function($item){return ($item->total>5);});
                break;
            case 'check_list_items':
                $objects = \Model\CheckListItems::
                where('end','<',\Carbon::now('UTC'))
                    -> where('end','>',\Carbon::now('UTC')->startOfMonth())
                    -> get()
                    -> filter(function($item){
                        return !$item->isCompleted();
                    });
                break;
            case 'temperatures_for_goods_in':
                $objects = \Model\TemperaturesForGoodsIn::where('compliant',0)->get();
                break;
            case 'food_incidents':
                $objects = \Model\FoodIncidents::whereStatus(0) -> get();
                break;
            case 'staff':
                $staff = \Model\Staffs::all();
                $objects = $staff->filter(function($item){
                    if(strtotime($item->created_at) <= 0){
                        $item->created_at = \Carbon::now('UTC')->subMonths(1);
                        $item->update();
                    }
                    $diff = \Carbon::createFromFormat('Y-m-d H:i:s', $item->created_at,'UTC')->diff(\Carbon::now('UTC'));
                    return (($item->healthQuestionnaires->count()==0) && ($diff->days > 7));
                });
                break;
            case 'users':
                $objects = \User::with([
                    'stats'=>function($query){$query -> where('created_at','>',\Carbon::now('UTC')->subDays(7));}
                ])->get()->filter(function($item){
                    $createdAt = \Carbon::parse($item->created_at);
                    return (($item->stats->count()==0) && $item->hasRole('local-manager') && ($createdAt->diffInDays(\Carbon::now()) >= 7));
                });
                break;
            case 'temperatures_for_pods':
                $objects = \Model\TemperaturesForPods::
                    where('timestamp','>',\Carbon::now('UTC')->startOfWeek()->timestamp) ->
                    where(function ($query) {
                        $query->whereNotNull('invalid_id');
                        $query->where('invalid_id', '>', 0);
                    }) ->
                    select('*', \DB::raw('count(id) as total'))->
                    groupBy('area_id','unit_id')->
                    get()->filter(function($item){return ($item->total>5);});
                break;
            case 'temperatures_for_probes':
                $objects = \Model\TemperaturesForPods::
                where('timestamp','>',\Carbon::now('UTC')->startOfWeek()->timestamp)
                    -> where('invalid_id','>',0)
                    -> limit(6)
                    -> get();
                $objects = ($objects->count() > 5) ? $objects->take(1) : null;
                break;
        }
        if($objects && $objects->count()){
            foreach($objects as $object) {
                //echo "\n".$object->getTable();
                $self::init($object);
            }
        }
    }

    public static function init($object)
    {
        $self = new self();
        $table = $object->getTable();

        switch($table){
            case 'units': $unitId = $object->unit_id;
                break;
            case 'users': $unitId = ($unit = $object->units()->first()) ? $unit->id : null;
                break;
            default: $unitId = $object->unit_id;
                break;
        }
        if(!$unitId) return NULL;
        $exist = \Model\Scores::whereTargetType($table) -> whereTargetId($object->id) -> whereUnitId($unitId) -> first();
        $scores = \Config::get('scores.sections.' . ($table) . '.value');

        if(!$exist && $scores) {

            $repo = $object -> repository();
            if ($repo && ($repoData = $repo -> getScoresData())) {
                $data = $self -> prepareObjectData($object, $unitId);
                $self -> checkStart($unitId);
                $data = array_merge($data, $repoData);
            }
            return $repoData ? \Model\Scores::create($data) : NULL;
        }
        return NULL;
    }

    public function getStartData($unitId)
    {
        return [
            'unit_id'       => $unitId,
            'target_type'   => 'units',
            'target_id'     => $unitId,
            'value'         => 0,
            'scores'        => \Config::get('scores.points.start'),
            'type'          => 'start',
            'message'       => $this->textStart,
        ];
    }

    public function checkStart($unitId)
    {
        $exist = \Model\Scores::whereUnitId($unitId)->whereType('start')->first();
        if(!$exist)
            $exist = \Model\Scores::firstOrCreate($this->getStartData($unitId));
        return $exist;
    }

    public function prepareObjectData($object, $unitId)
    {
        $lastScores = $this->getLastScores($unitId);
        $scoresData  = $this->mathScores($lastScores, $object->getTable());
        return [
            'unit_id'       => $unitId,
            'target_type'   => $object -> getTable(),
            'target_id'     => $object -> id,
            'value'         => $scoresData['value'],
            'type'          => $scoresData['type'],
            'scores'        => $scoresData['summary']
        ];
    }

    public function getLastScores($unitId)
    {
        $scores = \Model\Scores::whereUnitId($unitId)->whereRaw('id IN (SELECT max(id) FROM scores GROUP BY unit_id)')->first();
        if(!$scores){
            $scores = $this->checkStart($unitId);
        }
        return $scores->scores;
    }

    public function mathScores($initialScores, $table)
    {
        $max = \Config::get('scores.points.max');
        $min = \Config::get('scores.points.min');
        $scoresValue = \Config::get('scores.sections.'.$table.'.value');
        $scoresType  = \Config::get('scores.sections.'.$table.'.type');
        if(!integerValue($scoresValue) || !$scoresType)
            return false;

        $minus = $scoresValue < 0 ? true : false;

        if($scoresType == 'percent'){
            if($initialScores === 0)
                $nv = 1;
            else{
                $nv = (($initialScores * $scoresValue) / 100);
            }
        }
        elseif($scoresType == 'points'){
            $nv = $scoresValue;
        }

        if($nv > -1 && $nv < 0)
            $nv = -1;
        elseif($nv < 1 && $nv > 0)
            $nv = 1;

        if($minus && ($nv > 0) || !$minus && ($nv < 0))
            $nv = $nv * (-1);

        $summary = ($initialScores + $nv);
        $summary = ($summary > $max) ? $max : $summary;
        $summary = ($summary < $min) ? $min : $summary;

        $newScores['value'] = $scoresValue;
        $newScores['type']  = $scoresType;
        $newScores['summary'] = $summary;

        return $newScores;
    }
}