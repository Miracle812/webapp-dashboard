<?php
namespace Websocket\Pods\NaviSockets;

class BaseSockets
{
    public $hub;
    public $scheme = [
        'cmd'         => ['lng' => 2, 'type' => 'hex'],
        'hub_id'      => ['lng' => 6, 'type' => 'hex'],
        'pod_id'      => ['lng' => 3, 'type' => 'hex'],
        'flag'        => ['lng' => 1, 'type' => 'hex'],

        'year'        => ['lng' => 2, 'type' => 'dec'],
        'month'       => ['lng' => 1, 'type' => 'dec'],
        'day'         => ['lng' => 1, 'type' => 'dec'],
        'hour'        => ['lng' => 1, 'type' => 'dec'],
        'minute'      => ['lng' => 1, 'type' => 'dec'],

        'date_pod'    => ['lng' => 6, 'type' => 'hex_date'],
        'date_from'   => ['lng' => 6, 'type' => 'date_hex'],
        'date_to'     => ['lng' => 6, 'type' => 'date_hex'],

        'temperature' => ['lng' => 2, 'type' => 'tp1'],
        'counter'     => ['lng' => 4, 'type' => 'hex'],
        'lost'        => ['lng' => 1, 'type' => 'hex'],
        'alert_min'   => ['lng' => 2, 'type' => 'tp2'],
        'alert_max'   => ['lng' => 2, 'type' => 'tp2'],
        'alert_min_pod'   => ['lng' => 2, 'type' => 'tp1'],
        'alert_max_pod'   => ['lng' => 2, 'type' => 'tp1'],
        'voltage'     => ['lng' => 1, 'type' => 'vlt'],
        'crc'         => ['lng' => 2, 'type' => 'hex'],
    ];

    public function hexStr($array,$start,$lenght) //hex value
    {
        return implode('',array_slice($array, $start, $lenght));
    }

    public function cnvTemp($valueHex) //hex value
    {
        //temp range from -50 : +70oC
        $value = null;
        if( strlen($valueHex) == 4 ){
            $limit   = 10000;
            $complet = hexdec('0'.$limit);
            $value   = hexdec($valueHex);
            if($value >= $limit && $value < $complet){
                $value = - ( ($complet) - $value );
            }
        }
        return is_numeric( $value ) ? number_format(($value/10),1) : null;
    }

    public function cnvTempZmq($value)
    {
        $number = (int)str_replace('.','',sprintf("%.1f", $value));
        return dechex( $number < 0 ? (hexdec('010000') + $number) : $number );
    }

    public function cnvVolt($valueHex)
    {
        $value = null;
        if( strlen($valueHex) == 2 ){
            $value = implode('.',str_split($valueHex));
        }
        return $value;
    }

    public function cnvDate($value, $type)
    {
        $matrix = [ 'year', 'month', 'day', 'hour', 'minute' ];

        $dt = \Carbon::now();

        if($type == 'date'){
            $start = 0;
            $array = str_split(($value),2);
            foreach($matrix as $val){
                $lng = $this -> scheme[$val]['lng'];
                $dt -> $val = hexdec( $this -> hexStr($array,$start,$lng) );
                $start = $start + $lng;
            }
            $date = $dt;
        }

        else{  //date->hex //carbon Date
            $date = '';
            foreach($matrix as $val){
                $lng = $this -> scheme[$val]['lng'];
                $date .= $this->stringLng(dechex($dt->$val), $lng*2);
            }

        }
        return $date;
    }

    public function converter($value, $type)
    {
        $out = $value;
        switch ($type){
            case 'dec' : $out = hexdec($value); break;
            case 'tp1' : $out = $this -> cnvTemp($value); break;
            case 'tp2' : $out = $this -> cnvTempZmq($value); break;
            case 'vlt' : $out = $this -> cnvVolt($value); break;
            case 'hex_date' : $out = $this -> cnvDate($value,'date'); break;
            case 'date_hex' : $out = $this -> cnvDate($value,'hex'); break;
            case 'vlt' : $out = $this -> cnvVolt($value); break;
            case 'hex' : $out = $value; break;
        }
        return $out;
    }

    public function stringLng($hex,$length)
    {
        $str = str_repeat("0", $length);
        return substr($str.$hex,-$length);
    }

    public function getFlagByCmd($cmd){
        $flag = '00';
        switch ($cmd) {
            case '0069' : $flag = '04'; break;
            case '006A' : $flag = '04'; break;
            case '006B' : $flag = '04'; break;
        }
        return $flag;
    }

    public function getSchemeByCmd($cmd) //hex value for receive
    {
        $scheme = [];

        switch ($cmd) {
            case '0060': $scheme = ['cmd','hub_id','pod_id','date_from','date_to','crc']; break; //protocol & email = ok
            case '0061': $scheme = ['cmd','hub_id','pod_id','date_from','date_to','crc']; break; //protocol
            case '0062': $scheme = ['cmd','hub_id','pod_id','date_pod','flag','temperature','voltage','crc']; break; //protocol & email = ok
            case '0063': $scheme = ['cmd','hub_id','pod_id','flag','counter','crc']; break; //protocol & email = ok

            case '0065': $scheme = ['cmd','hub_id','pod_id','crc']; break; //protocol email = ok
            case '0066': $scheme = ['cmd','hub_id','pod_id','crc']; break; //protocol
            case '0067': $scheme = ['cmd','hub_id','pod_id','flag','alert_min_pod','alert_max_pod','crc']; break; //protocol email = ok

            case '0068': $scheme = ['cmd','hub_id','pod_id','flag','alert_min','alert_max','crc']; break; //email   ??????? = kevin

            case '0069': $scheme = ['cmd','hub_id','pod_id','alert_min','alert_max','flag','crc']; break;//protocol + email = ok
            case '006A': $scheme = ['cmd','hub_id','pod_id','alert_min','alert_max','flag','crc']; break; //protocol & email = ok
            case '006B': $scheme = ['cmd','hub_id','pod_id','alert_min','alert_max','flag','crc']; break; //protocol + email = ok

            case '0070': $scheme = ['cmd','hub_id','pod_id','date_pod','flag','lost','temperature','voltage','crc']; break; //protocol //email = ok, done
            case '0071': $scheme = ['cmd','hub_id','pod_id','flag','counter','crc']; break; // protocol + email = ok, done
        }
        return $scheme;
    }

}