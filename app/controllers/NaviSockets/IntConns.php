<?php namespace NaviSockets;

class IntConns extends BaseSockets {

    private $dns = "tcp://localhost:5555";
    private $socket;

    public function __construct()
    {
        $this -> socket = new \ZMQSocket(new \ZMQContext(), \ZMQ::SOCKET_REQ, 'IntConn');
    }

    public function connect()
    {
        $dns       = $this -> dns;
        $socket    = $this -> socket;
        $connected = $this -> checkConnection($dns,$socket);
        if($connected)
        {
            $socket -> send(1);
            $message = $socket -> recv();
            echo "<p>Server said: {$message}</p>";
            $this->disconnect();
        }
    }

    public function disconnect()
    {
        $this->socket->disconnect($this->dns);
    }



    public function checkConnection($dns, $socket)
    {
        $endpoints = $socket -> getEndpoints();
        $endpoints = $endpoints['connect'];

        if (!count($endpoints) || !in_array($dns, $endpoints))
        {
            try
            {
                $this->checkConnection($dns, $socket->connect($dns));
            }
            catch(\Exception $exception)
            {
                return false;
            }
        }
        else
            return  1;

    }

    public function getMessage($msg){

    }

    public function sendMessage($msg){

    }

}