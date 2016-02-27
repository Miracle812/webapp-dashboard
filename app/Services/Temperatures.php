<?php namespace Services;

use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Request;

class Temperatures extends \BaseController {

    var $options = ['url'=>'/temperatures/'],
        $rule_message = 'From {valid_min} to {valid_max} {celsius} are considered valid, outside valid and between {warning_min} and {warning_max} {celsius} are considered invalid / warning, outside warnings range is danger zone.';

    public function commonVerifier($ruleArea,$item)
    {
        $validMin = $ruleArea -> valid_min;
        $validMax = $ruleArea -> valid_max;
        $warningMin = $ruleArea -> warning_min;
        $warningMax = $ruleArea -> warning_max;

        $rule = \Model\TemperaturesLogRules::firstOrCreate(['valid_min'=>$validMin,'valid_max'=>$validMax,'warning_min'=>$warningMin,'warning_max'=>$warningMax]);
        $item -> update (['rules_id'=>$rule -> id]);
        $temp = $item -> temperature;

        if(($temp >= $validMin) && ($temp <= $validMax))
            return true;
        elseif($temp < $validMin && $temp > $warningMin)
            $result = ['warning', 'min', $validMin];
        elseif($temp > $validMax && $temp < $warningMax)
            $result = ['warning', 'max', $validMax];
        elseif($temp < $validMin && $temp < $warningMin)
            $result = ['danger', 'min', $validMin];
        else//($temp > $validMax && $temp > $alarmMax)
            $result = ['danger', 'max', $validMax];
        $invalid = \Model\TemperaturesLogInvalid::firstOrCreate(['rules_id'=>$item->rule->id,'type'=>$result[0],'exceed'=>$result[1],'temperature'=>$result[2]]);
        $item -> update(['invalid_id'=>$invalid -> id]);
        return false;
    }

    public function lastTemperatures($area_id = null)
    {
        $unitId = \Auth::user()->unitId();
        $pods =  \Model\TemperaturesForPods::
        whereRaw('id IN (SELECT max(id) FROM temperatures_for_pods GROUP BY area_id)')
            ->where('unit_id','=', $unitId);
        $probes =  \Model\TemperaturesForProbes::
        whereRaw('id IN (SELECT max(id) FROM temperatures_for_probes GROUP BY area_id)')
            ->where('unit_id','=', $unitId);

        if ($area_id) {
            $pods   -> where('area_id','=',$area_id);
            $probes -> where('area_id','=',$area_id);
        }

        $pods   = $pods -> get();
        $probes = $probes -> get();
        $collection = $pods->merge($probes);
        $temperatures = $collection ->sortBy(function($sort){
            return $sort->created_at;
        })->reverse();
        return $temperatures;
    }

    public function getRangeDates($date_from,$options=[])
    {
        $startDay = 'Y-m-d 00:00';
        $endDay   = 'Y-m-d 23:59';
        $today    = \Carbon::now()->endOfDay();

        if ($date_from){
            switch($date_from){
                case 'last-100':
                    $dateFrom = date($startDay, strtotime('2000-01-01'));
                    $dateTo   = $today;
                    $xaxisOptions = 'mode: "time", timeformat: "%m-%d", tickSize: [1, "day"]';
                    break;
                case 'today':
                    $dateFrom = date($startDay);
                    $dateTo   = $today;
                    $xaxisOptions = 'mode: "time",timeformat: "%H:%M",tickSize: [1, "hour"]';
                    break;

                case 'this-week':
                    $dateFrom = date($startDay, strtotime('Last Monday'));
                    $dateTo   = date($endDay,   strtotime('this week'));
                    $xaxisOptions = 'mode: "time",timeformat: "%m-%d %H:%M",tickSize: [1, "day"]';
                    break;

                case 'this-month':
                    $dateFrom = date('Y-m-01 00:00', strtotime('this month'));
                    $dateTo   = $today;
                    $xaxisOptions = 'mode: "time",timeformat: "%m-%d",tickSize: [1, "day"]';
                    break;

                case 'last-month':
                    $dateFrom = date('Y-m-01 00:00', strtotime('last month'));
                    $dateTo   = date('Y-m-t 23:59', strtotime('last month'));
                    $xaxisOptions = 'mode: "time",timeformat: "%m-%d",tickSize: [1, "day"]';
                    break;

                case 'this-year':
                    $dateFrom = date($startDay, strtotime('last year'));
                    $dateTo   = $today;
                    $xaxisOptions = 'mode: "time",timeformat: "%m-%d",tickSize: [1, "day"]';
                    break;
            };
        }
        return ['from' => $dateFrom,'to' => $dateTo, 'options' => in_array('xaxis',$options)?['xaxis'=>$xaxisOptions]:[]];
    }


    public function contentBuilder($temperatures,$type)
    {
        $areaId = isset($type['area'])  ? $type['area']  : NULL;
        if($areaId){
            if($tArea = $temperatures->first()->area){
                $table = $tArea->getTable();
                $model = '\Model\\'.ucfirst(camel_case($table));
                $area = $model::find($areaId);
                $link = '/temperatures/'.$area->group.'/'.$areaId;
                $linkName = $area->name;
            }
        }
        $group  = isset($type['group']) ? $type['group'] : NULL;
        if($group){
            $link = '/temperatures/'.$group;
            $linkName = ucfirst($group);
        }

        $options = [];

        if($areaId){
            if($area->group == 'probes'){
                if($areaId && $area->isChilling())
                    $options = $this->getContentAreaProbesChilling($temperatures);
                else
                    $options = $this->getContentAreaProbes($temperatures);
            }
            else
                $options = $this->getContentAreaPods($temperatures);

        }
        else{
            if($group == 'probes')
                $options = $this->getContentGroupProbes($temperatures);
            else
                $options = $this->getContentGroupPods($temperatures);
        }
        return $options;
    }

    public function getContentGroupProbes($temperatures){
        $options = [];
        foreach ($temperatures as $temperature) {
            $statusHtml = $this->getPopoverButton($temperature);
            $options[] = [
                strtotime($temperature->created_at),
                //date('H:i', strtotime($row->created_at)) . ' <span class="label text-primary">' . $row->created_at() . '</span>',
                $temperature->created_at(),
                '<a class="md-btn-ico md-btn-sm md-btn-green col-sm-12 " data-toggle="tooltip" title="Select Appliance to view all temperatures" href="'.$this->options['url'].$temperature->area->group.'/'.$temperature->area->id.'"><i class="fa fa-link m-r"></i><span class="font-bold">'.$temperature->area->name.'</span></a>',
                '<div class="font-bold uk-text-black uk-text-center">'.$temperature->temperature.'&#x2103</div>',
                $temperature->item_name,
                $temperature->staff_name,
                //$statusHtml,
                $this->getButtonTodayStatus($temperature->isStatusValidToday())
            ];
        }
        return $options;
    }

    public function getContentGroupPods($temperatures){
        $options = [];

        foreach ($temperatures as $row) {
            $statusHtml = $this->getPopoverButton($row);
            $options[] = [
                strtotime($row->created_at),
                //date('H:i', strtotime($row->created_at)) . ' <span class="label text-primary">' . $row->created_at() . '</span>',
                $row->created_at(),
                '<a data-uk-tooltip="{cls:\'long-text\'}" title="Select Appliance to view all temperatures" href="'.$this->options['url'].$row->area->group.'/'.$row->area->id.'"><i class="material-icons m-r">link</i><span class="font-bold">'.$row->area->name.'</span></a>',
                '<div class="uk-text-black uk-text-center">'.$row->temperature.'&#x2103</div>',
                '<div class="uk-text-black uk-text-center">'.$row->battery_voltage.' V</div>',
                //'<div class="font-bold uk-text-black text-right">'.$row->battery_level.' %</div>',
                //$statusHtml,
                \HTML::ownOuterBuilder($this->getButtonTodayStatus($row->isStatusValidToday()))
            ];
        }
        return $options;
    }

    public function getButtonTodayStatus($status)
    {
        switch($status) {
            //case 1:$class = 'md-btn-default'; $text = 'NO TEMPS';
            case 1:$class = 'uk-badge-danger'; $text = 'INVALID';
                break;
            case 2:$class = 'uk-badge-success'; $text = 'VALID';
                break;
            case 3:$class = 'uk-badge-danger'; $text = 'INVALID';
                break;
            default:$class = 'uk-badge-default'; $text = 'N/A';
                break;
        }
        return '<span class="uk-badge '.$class.'">'.$text.'</span>';
    }

    public function getContentAreaProbes($temperatures){
        $options = [];
        foreach ($temperatures as $row) {
            $statusHtml = $this->getPopoverButton($row);
            $options[] = [
                strtotime($row->created_at),
                $row->created_at(),
                $row->staff_name,
                $row->device_name,
                $row->item_name,
                \HTML::ownOuterBuilder($row->temperature.'&#x2103'),
                \HTML::ownOuterBuilder($statusHtml),
                \HTML::ownOuterBuilder($this->getResolveButton($row))
            ];
        }
        return $options;
    }

    public function getResolveButton($temperature)
    {
        $resolveBtn = '';
        if($temperature->invalid && ($outstandingTask = $temperature->outstandingTask)){
            $title = (!$outstandingTask->status?'Not ':'').'Resolved';
            $class = $outstandingTask->status?'success':'danger';
            $button = '<span class="uk-badge uk-text-center pointer uk-badge-'.$class.'">'.$title.'</span>';
            $resolveBtn = ($outstandingTask->status?\HTML::ownPopoverButton($outstandingTask->action_todo,$button,$title):$button);
        }
        return $resolveBtn;
    }

    public function getContentAreaPods($temperatures){
        $options = [];
        foreach ($temperatures as $row) {
            $options[] = [
                strtotime($row->created_at),
                $row->created_at(),
                '<div class="uk-text-center">'.$row->pod_name.'</div>',
                '<div class="uk-text-black uk-text-center">'.$row->temperature.'&#x2103</div>',
                '<div class="uk-text-black uk-text-center">'.$row->battery_voltage.' V</div>',
                //'<div class="font-bold uk-text-black uk-text-center">'.$row->battery_level.' %</div>',
                \HTML::ownOuterBuilder($this->getPopoverButton($row)),
                \HTML::ownOuterBuilder($this->getResolveButton($row))
            ];
        }
        return $options;
    }

    public function getContentAreaProbesChilling($temperatures)
    {
        $options = [];
        foreach ($temperatures as $row)
        {
            $pair = $row -> pair ? : false;

            if ($pair && ($pair -> id > $row -> id)){
                continue;
            }
            if (in_array($row->status,[0,1])){
                $firstTemp = $row -> temperature;
                $lastTemp  = $pair ? $pair -> temperature : false;
                $data      = $pair ? : false;
            }
            else{
                $firstTemp = $pair ? $pair -> temperature : false;
                $lastTemp  = $row -> temperature;
                $data      = $row;
            }

            if ($data){
                $statusHtml = $this -> getPopoverButton($data);
            }
            $options[] = [
                strtotime($row->created_at),
                //date('H:i', strtotime($row->created_at)) . ' <span class="label text-primary">' . $row->created_at() . '</span>',
                $row->created_at(),
                $row->staff_name,
                $row->device_name,
                $row->item_name,
                $firstTemp ? '<span class="uk-text-black">'.$firstTemp.'&#x2103</span>' : "N/A",
                $lastTemp ? '<span class="font-bold uk-text-black">'.$lastTemp.'&#x2103</span>' : "N/A",
                isset($statusHtml) ? $statusHtml : 'N/A',
                $this->getResolveButton($row)
            ];
        }
        return $options;
    }

    public function getPopoverButton($temperature,array $options = [])
    {
        /*
        $defOptions = ['placement'=>'top','in_title'=>false];
        $options = array_merge($defOptions,$options);
        $popover = [
            'data-original-title' => ["<button type='button' class='close pull-right' data-dismiss='popover'>×</button>"],
            'title'         => [''],
            'class'         => ['md-btn-xs', 'uk-text-center', 'pointer'],
            'data-placement'=> [$options['placement']],
            'data-html'     => ['true'],
            'data-toggle'   => ['popover'],
            'data-content'  => [],
        ];

        $html = $startOuterBegin = '<div ';
        $endOuter        = '</div>';
        $startOuterEnd   = '>';
        $content = $this -> popoverContentBuilder( $temperature );
        list($class,$title) = $this -> getTitleClass( $temperature -> invalid );
        foreach($popover as $key => $values){
            if($key == 'class')
                array_push ($values, $class);
            switch($key){
                case 'data-content' : $values = [$content]; break;
                case 'data-original-title' : $values[] = "<div class='type-".strtolower($title)."'>".strtoupper($title)."</div>"; break;
            }
            $html .= $key.'="'.implode(" ", $values).'" ';
        }
        $title = $options['in_title'] ? $temperature->temperature . '&#x2103  '. $title : $title;

        $html .= $startOuterEnd.strtoupper($title).$endOuter;
        return $html;
        */
        $defOptions = ['placement'=>'top','in_title'=>false];
        $options = array_merge($defOptions,$options);
        $popover = [
            'title'         => [''],
            'href'          => ['javascript:;'],
            'class'         => ['uk-badge'],
            'data-uk-tooltip'=> ["{cls:'long-text', pos:'" . $options['placement'] . "'}"],
        ];

        $html = $startOuterBegin = '<a ';
        $endOuter        = '</a>';
        $startOuterEnd   = '>';
        $content = $this -> popoverContentBuilder( $temperature );
        list($class,$title) = $this -> getTitleClass( $temperature -> invalid );
        foreach($popover as $key => $values){
            if($key == 'class')
                array_push ($values, $class);
            if ($key == 'title') $values = [$content];
            $html .= $key.'="'.implode(" ", $values).'" ';
        }
        $title = $options['in_title'] ? $temperature->temperature . '&#x2103  '. $title : $title;

        $html .= $startOuterEnd.strtoupper($title).$endOuter;

        return $html;
    }

    public function getTitleClass($invalid)
    {
        return $invalid ? ['uk-badge-'.$invalid -> type,\Lang::get('common/general.'.$invalid -> type)] : ['uk-badge-success',\Lang::get('common/general.valid')];
    }

    public function popoverContentBuilder($item)
    {
        if(!$item->rule){
            $item->delete();
            return false;
        }

        $item->rule->celsius = $celsius = '&#x2103';
        $message = $this -> rule_message;
        preg_match_all ('/{([^{]+?)}/', $message, $matches);
        if($matches){
            foreach ($matches[1] as $key => $match){
                if(!is_null($item->rule->$match))
                    $message = str_replace('{'.$match.'}', $item->rule->$match, $message);
            }
        }
        if($item -> invalid_id) {
            switch ($item -> invalid -> type) {
                case 'warning' :
                    $text = $item -> invalid -> exceed . ' valid range';
                    break;
                case 'danger' :
                    $text = $item -> invalid -> exceed . ' warning range';
                    break;
            }
            $message .= '<br><b>Exceed:</b>' . $text . ':' . $item->invalid->temperature . $celsius . '<br>';
        }
        return $message;
    }
    /*
        public static function getExceedesMessageProbe($temperature)
        {
            $bid = 'tem-pro-'.$temperature->id;
            return
                    '<b>Exceeds:</b> '.ucfirst($temperature->invalid->exceed).' value '.$temperature->invalid->temperature.'&#x2103<br>'.
                    '<span id="moreless-'.$bid.'" class="text-muted hide">'.
                        '<b>'.$temperature->group->name.' / '.$temperature->area->name.'</b><br>'.
                        '<b>Last temp:</b> '.$temperature->temperature.'&#x2103<br>'.
                        '<b>Staff:</b> '.$temperature->staff_name.'<br>'.
                        '<b>Item:</b> '.$temperature->item_name.'<br>'.
                        '<b>Create at:</b> '.$temperature->created_at().
                    '</span> <br>'.
                  self::moreLessButton($bid);
        }

        public static function moreLessButton($bid){
            return '<button class="btn md-btn-xs  md-btn-default" data-toggle="class:show" href="#moreless-'.$bid.'">
                    <i class="fa fa-plus text"></i>
                    <span class="text">More</span>
                    <i class="fa fa-minus text-active"></i>
                    <span class="text-active">Less</span>
                    </button>';
        }
    */
    public function getTemperaturesEntity($group, $area_id = null,  $range = [], $limit=null)
    {
        $unitId = $this->auth_user->unitId();
        switch($group){
            case 'pods':
                $temperaturesModel = new \Model\TemperaturesForPods();
                $areasId = \Model\TemperaturesPodsAreas::whereUnitId($unitId)->whereType('area')->lists('id');
                break;
            case 'probes'  :
                $temperaturesModel = new \Model\TemperaturesForProbes();
                $areasId = \Model\TemperaturesProbesAreas::whereUnitId($unitId)->lists('id');
                break;
        }
        $temp = $temperaturesModel;
        $temp = $temp -> whereUnitId($unitId);
        if(\Request::has('filter') && \Request::get('filter')=='invalid'){
            $temp = $temp -> where('invalid_id','>',0);
        }
        if($area_id){
            $temp = $temp -> where('area_id', '=', $area_id);
        }
        else{
            if($areasId){
                $temp = $temp -> whereIn('area_id',$areasId);
            }
        }

        if($range['data_range'] == 'last-values'){
            $temp = $temp -> whereRaw('id IN (select max(id) from temperatures_for_'.$group.' group by area_id)');
            //$temp = $temp -> orderBy('id','DESC')->groupBy('area_id')->limit(1);
        }

        if($range['from']){
            $temp = $temp -> where('created_at','>=',$range['from']);
        }

        if($range['to']){
            $temp = $temp -> where('created_at','<=',$range['to']);
        }
        $temp -> orderBy('created_at','DESC');
        $temp -> limit($limit);
        $temp = $temp -> get();
        return $temp;
    }



//for display
/*
    public function getValidTemperature($item){
        if($item && $item->invalid_exceed){
            $out = [
              'current' =>$item->temperature,
              'range'   =>$item->invalid_range,
              'type'    =>$item->invalid_exceed,
              'exc_temp'=>$item->invalid_exceed_temp,
            ];
        }
        return $out;
    }
*/
}