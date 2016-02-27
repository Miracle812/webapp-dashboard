<?php namespace Websocket\Pods\NaviSockets;

class ExtConns extends BaseSockets
{
    private $dataType = 'bin';
    public function sender($socket, $message)
    {
        $sync = 'AA'; $stx = '75';
        $lng  = $this -> stringLng( dechex(strlen($message)/2),2 );
        $message = $sync.$stx.$lng.$message;
        $message = strtoupper($message);

        if($this -> dataType == 'bin')
            $message = hex2bin($message);

        return $socket -> getSocket() -> send( $message );
    }

    public function receiver($message)
    {
        if ( mb_detect_encoding($message, 'ASCII', true) )
        {
            if( preg_match('/[0-9A-Fa-f]+/', $message) )
            {
                $this->dataType = 'hex';
            }
        }
        else{
            if(preg_match('/[0-9A-Fa-f]+/', $str = bin2hex($message)))
            {
                $this->dataType = 'bin';
                $message = $str;
            }
        }
        if($message)
        {
            $podRepo = \App::make('PodsRepository');
            $podRepo -> logMessage($message, $this -> hub);
        }
        $sync = '55'; $stx = '7A';
        $scSx = preg_match('/^'.$sync.$stx.'/',strtoupper($message));
        if(!$message || strlen($message) < 10 || !$scSx)
            return false;

        $data = $this -> prepareData( str_split(($message),2) );
        return  $this -> processData($data);
    }

    public function prepareData($hexArr)
    {
        $data =  new \stdClass();
        $data -> sync   = $hexArr[0];
        $data -> stx    = $hexArr[1];
        $data -> lenght = $hexArr[2];
            $dRow = array_slice($hexArr, 3, hexdec($data -> lenght));
        $data -> cmd    = $this -> hexStr($dRow,0,2);
        $data -> data   = implode('',$dRow);
        return $data;
    }

    public function processData(\stdClass $data)
    {
        $cmd       = $data -> cmd;
        $dArr      = str_split( ($data -> data),2 );
        $cmdScheme = $this -> getSchemeByCmd($cmd);
        $scheme = $this -> scheme;
        $start     = 0;
        if( count($cmdScheme) ) {
            foreach ($cmdScheme as $key) {
                $hexStr = $this -> hexStr($dArr, $start, $lng = $scheme[$key]['lng']);
                $data -> $key = $this -> converter($hexStr, $scheme[$key]['type']);
                $start = $start + $lng;
            }
        }
        return $this -> cmdAction($data);
    }

    public function reverseProcessData(\stdClass $data)
    {
        $data = $data -> data;
        $cmd  = $data -> cmd;

        $data -> flag = $this -> getFlagByCmd($cmd);
        $data -> crc = '0000';

        $cmdScheme = $this -> getSchemeByCmd($cmd);
        $scheme = $this -> scheme;
        $out = '';
        if( count($cmdScheme) ) {
            foreach ($cmdScheme as $key) {
                $mtx  = $scheme[$key];
                $out .= $this -> stringLng (
                    $this -> converter ( $data -> $key, $mtx['type'] ), ( $mtx['lng'] * 2 )
                );
            }
        }
        return $out;
    }

    public function cmdAction(\stdClass $data)
    {
        $podRepo = \App::make('PodsRepository');


        switch ($data -> cmd)
        {
            case '0062' :
                $out = in_array($data -> flag,['00','01','02']) ?
                    $podRepo -> getResponse( $data, $podRepo -> storeFromSocket( $data , $this -> hub ) ) :
                    null;
                $out =  $data -> flag == '01' ? null : $out;
                break;

            case '0067' :
                $out = in_array($data -> flag,['00','01','02']) ?
                    $podRepo -> getResponse( $data, $podRepo -> setAlertFromSocket( $data,$this -> hub)) :
                    null;
                $out =  $data -> flag == '01' ? null : $out;
                break;

            case '006B' :
                $podRepo -> setAlertFromSocket( $data,$this -> hub);
                $out = null; break;

            case '0070' :
                $out = in_array($data -> flag,['00','01','02']) ?
                    $podRepo -> getResponse( $data, $podRepo -> storeFromSocket( $data , $this -> hub ) ) :
                    null;
                $out =  $data -> flag == '01' ? null : $out;
                break;

            default: $out = null; break;
        }
        return $out;
    }
}