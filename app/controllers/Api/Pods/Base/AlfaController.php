<?php namespace Api\Pods\Base;

use Api\Pods\MasterController;

class AlfaController extends MasterController
{
    public $filterDuplicates = ['temperature','pod_ident','hub_log_id','area_id'];

    public function postIndex()
    {
        return $this->returnJson (['type'=>'error','message' => \Lang::get('/common/messages.route_404')]);
    }
    public function getIndex()
    {
        return $this->returnJson (['type'=>'error','message' => \Lang::get('/common/messages.route_404')]);
    }

    public function getTimestamp()
    {
        return $this->returnJson(['type'=>'success','data'=>\Carbon::now()->timestamp]);
    }

    public function findLastDuplicate(array $data)
    {
        $filterKeys = $this -> filterDuplicates;
        return \Model\TemperaturesForPods::wherePodIdent($data['pod_ident'])->whereTimestamp($data['timestamp'])->whereTemperature($data['temperature'])->first();
        /*
        $last = \Model\TemperaturesForPods::wherePodIdent($data['pod_ident'])->whereTimestamp($data['timestamp'])->first();
        if($last){
            return $last;
        } else {
            $last = \Model\TemperaturesForPods::whereRaw(' id IN (SELECT MAX(id) FROM temperatures_for_pods WHERE pod_ident = "'.$data['pod_ident'].'") ')->first();
        }
        if(!$last) return null;
        $dataArr = array_intersect_key($data, array_flip($filterKeys));
        $lastArr = array_intersect_key($last->toArray(), array_flip($filterKeys));
        $res     = array_diff($dataArr, $lastArr);

        return empty($res) ? $last : null;
        */
    }

    public function batteryLevel($voltage)
    {
        if ($voltage >= 3.2)                          {$lvl = 100;}
        elseif (($voltage < 3.2)  && ($voltage > 3))  {$lvl = 90;}
        elseif (($voltage <= 3)   && ($voltage > 2.8)){$lvl = 80;}
        elseif (($voltage <= 2.8) && ($voltage > 6))  {$lvl = 60;}
        elseif (($voltage <= 2.6) && ($voltage > 2.4)){$lvl = 45;}
        elseif (($voltage <= 2.4) && ($voltage > 2.2)){$lvl = 30;}
        elseif (($voltage <= 2.2) && ($voltage > 2))  {$lvl = 15;}
        elseif ($voltage <= 2)                        {$lvl = 5;}
        else {$lvl = 100;}
        return $lvl;
    }

    public function putTemperature()
    {
        $record = \Input::json()->get('data');
        $errorsMsg = $infoMsg = $successMsg = [];
        $rules = [
            'hub_id'           => 'required',
            'pod_id'           => 'required',
            'measurement_time' => 'required|integer',
            'temperature'      => 'required|numeric',
            //'battery_level'    => 'required|numeric|between:0,100',
            //'battery_voltage'  => 'required|numeric|between:0,12',
        ];
        $validator = \Validator::make($record, $rules);
        $errors = $validator->messages()->toArray();
        if (empty($errors))
        {
            $hub = \Model\TemperaturesHubsLogs::firstOrCreate(['hub_id' => $record['hub_id'],'ip' => \Request::getClientIp()]);
            $tempData = [
                'hub_log_id'      => $hub -> id,
                'pod_ident'       => $record['pod_id'],
                'battery_level'   => $this->batteryLevel($record['battery_voltage']),
                'battery_voltage' => number_format($record['battery_voltage'],3),
                'temperature'     => number_format($record['temperature'],3),
                'timestamp'       => $record['measurement_time']
            ];

            $pod = \Model\TemperaturesPodsSensors::whereIdentifier($record['pod_id'])->first();
            if (!$pod)
            {
                $pod = \Model\TemperaturesPodsSensorsNa::where('pod_ident',$record['pod_id'])->whereTimestamp($record['measurement_time'])->first();
                $pod ? $pod->update($tempData) : \Model\TemperaturesPodsSensorsNa::create($tempData);
                //$errorsMsg[] = 'Pod sensor (id: '.$record['pod_id'].' ) not assigned to any area/appliance.';
                //continue;
                return $this->returnJson(['type'=>'error','message'=>'Pod sensor (id: '.$record['pod_id'].' ) not assigned to any area/appliance.']);
            } else {
                if($area = $pod -> area()) {
                    $excluded = $area->excludeTimeframe;
                    $excluded = $excluded ? $excluded->isExcluded() : false;
                    if ($excluded) {
                        //$infoMsg[] = 'Storing temperatures is excluded by time frame for area (' . $area->name . ') .';
                        //continue;
                        return $this->returnJson(['type'=>'error','message'=>'Storing temperatures is excluded by time frame for area (' . $area->name . ') .']);
                    }
                    $pod -> update(['ip' => \Request::getClientIp()]);
                    $defData = [
                        'unit_id' => $area->unit_id,
                        'area_id' => $area->id,
                        'pod_id' => $pod->id,
                        'pod_name' => $pod->name,
                    ];
                    $completeData = array_merge($defData, $tempData);
                    $duplicate = $this -> findLastDuplicate($completeData);
                    if(\Mapic::isValidTimestamp($timestamp = $record['measurement_time'])){
                        $dateTs = \Carbon::createFromTimestamp($timestamp);
                    }else{
                        $dateTs = \Carbon::now();
                        $tempData['timestamp'] = $dateTs->timestamp;
                    }
                    if(!$duplicate) {
                        $newRecord = \Model\TemperaturesForPods::create(array_merge($defData, $tempData, ['created_at' => $dateTs, 'updated_at' => $dateTs]));
                        if ($newRecord) {
                            $tempService = new \Services\Temperatures();
                            $tempService->commonVerifier($area, $newRecord);
                            //$successMsg[] = 'New record has been added.';
                            //continue;
                            return $this->returnJson(['type'=>'success','data'=>'New record has been added.']);

                        } else {
                            //$errorsMsg[] = 'New record cannot be added.';
                            return $this->returnJson(['type'=>'success','data'=>'Record is duplicated.']);
                        }
                    } else {
                        $duplicate->update(['updated_at'=>$dateTs]);
                        //$errorsMsg[] = 'Record is duplicated.';
                        return $this->returnJson(['type'=>'success','data'=>'Record is duplicated.']);
                    }
                }
                else{
                    //$errorsMsg[] = 'Pod isn\'t assigned to any area.';
                    return $this->returnJson(['type'=>'error','message'=>'Pod isn\'t assigned to any area.']);
                }
            }
        } else {
            $errorsMsg[] = $this->ajaxErrors($errors, []);
            return $this->returnJson(['type'=>'error','message'=>'Invalid input data.']);
        }

        //$messages = [];
        //$messages = count($errorsMsg) ? array_merge($messages,['type'=>'error', 'message'=> $errorsMsg]) :$messages;
        //$messages = count($infoMsg) ? array_merge($messages,['type'=>'success', 'message'=> $infoMsg]) :$messages;
        //$messages = count($successMsg) ? array_merge($messages,['type'=>'success', 'message'=> $successMsg]) :$messages;
        //return $this->returnJson(['type' => 'report', 'message'=>$messages]);
    }
}