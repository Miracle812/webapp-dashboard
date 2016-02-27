<?php namespace Websocket\Pods;

use Evenement\EventEmitterInterface;
use Exception;
use Model\PoHubsResources;
use Ratchet\ConnectionInterface;
use SplObjectStorage;
use Illuminate\Console\Command;

class HubsPusher implements HubsPusherInterface
{
    public $connections;
    protected $sockets;
    protected $emitter;
    protected $id = 1;

    public function getHubBySocket(ConnectionInterface $conn)
    {
        foreach ($this -> connections as $next)
        {
            if ($next -> getSocket() === $conn)
            {
                return $next;
            }
        }
        return null;
    }

    public function refreshOnline()
    {
        \Model\HubsDevices::whereOnline(1)->update(['online' => 0]);
        foreach ($this -> connections as $next)
        {
            $socket = $next -> getSocket();
            \Model\HubsDevices::
                whereIp($socket -> remoteAddress)->
                whereResourceId($socket -> resourceId)->
                orderBy('id','DESC')->
                limit(1)->
                update(['online' => 1]);
        }
    }

    public function getSocketByData($data)
    {
        foreach ($this -> connections as $next)
        {
            $socket = $next -> getSocket();
            if ($socket)
            {
                $ip = $socket -> remoteAddress;
                $id = $socket -> resourceId;

                if ($id == $data -> id &&
                    $ip == $data -> ip)
                {
                    return $next;
                }
            }
        }
        return null;
    }

    public function setPushSocket($aa)
    {
        $this -> sockets = $aa;
    }

    public function getEmitter()
    {
        return $this -> emitter;
    }

    public function setEmitter(EventEmitterInterface $emitter)
    {
        $this -> emitter = $emitter;
    }

    public function getHubs()
    {
        return $this->connections;
    }

    public function __construct(EventEmitterInterface $emitter)
    {
        $this -> emitter = $emitter;
        $this -> connections = new SplObjectStorage();
        $this -> extConn = new NaviSockets\ExtConns();
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $hub =  new HubsSocket();
        $hub -> setId($conn -> resourceId);
        $hub -> setAddress($conn -> remoteAddress);
        $hub -> setSocket($conn);
        $hub -> saveHub();
        $this-> connections -> attach($hub);
        $this -> refreshOnline();
        //echo (PHP_EOL.date('Y-m-d H:i:s'));
        //echo (PHP_EOL."Open connection:" . $addr);
        //echo (PHP_EOL."Resource Id:" . $id);
        //$this -> emitter -> emit("open", [$hub]);
    }

    public function onClose(ConnectionInterface $conn)
    {
        $hub  =  $this->getHubBySocket($conn);
        $hub  -> saveHub(0);
        $this -> connections -> detach($conn);
        $this -> refreshOnline();

        //echo (PHP_EOL.date('Y-m-d H:i:s'));
        //echo (PHP_EOL."Closed connection IP:" . $conn -> remoteAddress);
        //echo (PHP_EOL."Resource Id:" . $conn -> resourceId);
        //$this -> emitter->emit("close", [$hub]);
    }

    public function onMessage(ConnectionInterface $conn, $message)
    {
        $this -> extConn -> hub = $hub = $this -> getHubBySocket($conn);
        $receiver = $this -> extConn -> receiver ($message);

        if(!in_array($receiver,[0,-1])) //-1 = invalid message //0 = no action for message //data string = answer
        $send = $this -> extConn -> sender ($hub,$receiver);
    }

    public function onZmqMessage($message)
    {
            $data = json_decode($message);
          $socket = $this -> getSocketByData($data -> socket);
        $response = $send = null;

        if($socket)
            $response = $this -> extConn -> reverseProcessData($data);
        if($response)
            $send = $this -> extConn -> sender ($socket,$response); //data = bin
        return $send;
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}
