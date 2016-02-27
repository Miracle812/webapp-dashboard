<?php namespace Websocket\Pods\NaviSockets;

class ZmqConns extends BaseSockets {

    private $host;
    private $port;
    private $dns;
    public  $connection;

    private $socket;

    public function __construct()
    {
        $cfg = 'websocket.server-';
        $this -> host = \Config::get($cfg.'zmq.addr', '127.0.0.1');
        $this -> port = \Config::get($cfg.'zmq.port', 5555);
        $this -> dns = 'tcp://'.$this->host.':'.$this->port;

        $context = new \ZMQContext();
        $socket  = new \ZMQSocket($context, \ZMQ::SOCKET_PUSH, 'persistent_1');
        $socket -> setSockOpt(\ZMQ::SOCKOPT_LINGER, 100);
        $socket -> setSockOpt(\ZMQ::SOCKOPT_IDENTITY, "PEER2");
        $this -> socket = $socket;

        $conn = $this -> checkConnection();
        if(!$conn){
            $conn = $this -> connect();
        }
        $this -> connection = $conn;
    }

    public function connect()
    {
        if($this -> checkServer() && !$this -> checkConnection()) {
            $connect = $this -> socket -> connect( $this -> dns );
            $serverOnline = $this -> checkConnection();
            return $serverOnline;
        }
        else
            return false;
    }

    function checkServer()
    {
        $socket = @fsockopen ($this->host, $this->port, $errno, $errstr, 10);
        $status = false;
        if ($socket){
            fclose($socket);
            $status = true;
        }
        return $status;
    }

    public function checkConnection()
    {
        $dns       = $this   -> dns;
        $socket    = $this   -> socket;
        $endpoints = $socket -> getEndpoints();
        $persistentId = $socket -> getPersistentId();
        $socketType = $socket -> getSocketType();
        $endpoints = $socket -> getEndpoints();
        return  in_array($dns, $endpoints['connect']) ? true : false;
    }

    public function sendMessage($msg)
    {
        return $this -> socket -> send($msg);
    }

    public function getResponse()
    {
        return $this -> socket -> recv();
    }

    public function disconnect()
    {
        //$serverOnline = $this -> checkConnection();
        $serverOnline = false;
        return $serverOnline ? $this -> socket -> disconnect($this -> dns) : true;
    }
}