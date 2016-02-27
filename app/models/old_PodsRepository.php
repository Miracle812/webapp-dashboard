<?php

class PodsRepository
{
    public function storeTemperatureFromSocket($data, $socket){

    }

    public function setAlertFromSocket($data, $socket)
    {
        $pod = \Model\PodsDevices::whereIdentifier($data -> pod_id)->first();
        $error = $pod ? false : true; //pod not exist

        if(!$error)
        {
            $area = $pod -> area;
            $error =  (!$area || !$area->group) ? true : false;
        }

        if(!$error)
        {
            $hub = $socket -> getHub();
            $hub -> ip = $socket -> address;
            $hub -> resource_id = $socket -> id;
            $hub -> updated_at = \Carbon::now();
            $hub -> update();

            $pod -> alert_min = $data -> alert_min_pod;
            $pod -> alert_max = $data -> alert_max_pod;

            $pod -> update();
        }
        return 1;
    }

    public function logMessage($message, $socket)
    {
        $hub = $socket -> getHub();
        $log = new \Model\HubsMessages();
        $log -> fill(['hub_id' => $hub->id, 'message' => $message]);
        return $log->save();
    }

    public function storeFromSocket($data, $socket)
    {
        $pod = \Model\PodsDevices::whereIdentifier($data -> pod_id)->first();

        $hub = $socket -> getHub();


        if( $hub -> identifier !== $data -> hub_id ) {
            $hub -> identifier = $data -> hub_id;
            $hub -> update();
        }

        $area = $pod ? $pod -> area : null;
        if($pod && $area && $area -> group)
        {
            $warningMin = $area -> valid_min;
            $warningMax = $area -> valid_max;
            $dangerMin = $area -> warning_min;
            $dangerMax = $area -> warning_max;

            $rule = \Model\TemperaturesLogRules::firstOrCreate(['valid_min'=>$warningMin,'valid_max'=>$warningMax,'warning_min'=>$dangerMin,'warning_max'=>$dangerMax]);

            $timestamp = $data -> date_pod -> timestamp;
            $temperature = \Model\TemperaturesForPods::
                    wherePodId($pod -> id)->
                    whereTimestamp($timestamp)->
                    whereTemperature($pod -> temperature)->
                    whereVoltage($pod -> voltage)->
                    first();

            if (!$temperature) {
                $new = new \Model\TemperaturesForPods();

                $new -> hub_id    = $hub -> id;
                $new -> area_id   = $area-> id;
                $new -> unit_id   = $area-> unit_id;
                $new -> pod_id    = $pod -> id;
                $new -> pod_ident = $pod -> identifier;
                $new -> pod_name  = $pod -> name;
                $new -> timestamp = $timestamp;
                $new -> temperature = $data -> temperature;
                $new -> voltage   = $data -> voltage;
                $new -> save();

                $tempService = new \Services\Temperatures();
                $isValid = $tempService->commonVerifier($area,$new);
                if (!$isValid)
                {
                    \Services\OutstandingTasks::create($new);
                }
            }
        }

        $count =  $this -> recordCounterByFlag($data -> cmd, $data -> flag, $data -> hub_id, $data -> pod_id);
        return $count;
    }

    public function recordCounterByFlag($cmd, $flag, $hubId, $podId)
    {
        switch ($cmd) {
            case '0067' :
            case '0068' : $ident = 'pod.'.$cmd.$podId; break;
            case '0070' : $ident = 'hub.'.$cmd.$hubId; break;
            default: $ident = 'pod.'.$podId; break;
        }

        if(!\Session::has($ident))
            \Session::put($ident,0);
        $count = \Session::get($ident);
        switch ($flag){
            case '00' :
                \Session::put($ident, $count = 1);
                break;
            case '01' :
                \Session::put($ident, $count = $count + 1);
                break;
            case '02' :
                \Session::put($ident, $count = $count + 1);
                \Session::forget($ident);
                break;
            default : $count = 0;
                break;
        }
        return $count;
    }

    public function getResponse($data,$count)
    {
        if($count !== -1) {
            switch ($data->cmd){
                case '0062' :
                    $cmd = '0063';
                    $hub = $data -> hub_id;
                    $pod = $data -> pod_id;
                    $flg = $data -> flag;
                    $cnt = $this -> decHexLng($count, 8);
                    $crc = '0000';
                    $data = $cmd . $hub . $pod . $flg . $cnt . $crc;
                    break;

                case '0067' :
                    $cmd = '0068';
                    $hub = $data -> hub_id;
                    $pod = $data -> pod_id;
                    $flg = $data -> flag;
                    $max = $this->cnvTempZmq($data -> alert_max_pod);
                    $min = $this->cnvTempZmq($data -> alert_min_pod);
                    $crc = '0000';
                    $data = $cmd . $hub . $pod . $flg . $min . $max. $crc;
                    break;

                case '0070' :
                    $cmd = '0071';
                    $hub = $data -> hub_id;
                    $pod = $data -> pod_id;
                    $flg = $data -> flag;
                    $cnt = $this -> decHexLng($count, 8);
                    $crc = '0000';
                    $data = $cmd . $hub . $pod . $flg . $cnt . $crc;
                    break;

                default: $data = 0;
                    break;
            }
            return $data;
        }
        return 0;
    }

    public function decHexLng($dec,$length)
    {
        $str = str_repeat("0", $length);
        return substr($str.dechex($dec),-$length);
    }

    public function cnvTempZmq($value)
    {
        $number = (int)str_replace('.','',sprintf("%.1f", $value));
        return dechex( $number < 0 ? (hexdec('010000') + $number) : $number );
    }
}