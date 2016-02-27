<?php

namespace Websocket\Pods\Command;


use Websocket\Pods\HubsPusher;
use Websocket\Pods\HubsPusherInterface;
use Websocket\Pods\HubsSocket;
use Websocket\Pods\HubsSocketInterface;

use Illuminate\Console\Command;

use Ratchet\ConnectionInterface;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;



class Serve
    extends Command
{
    protected $name        = "podsocket:serve";
    protected $description = "Socket 4 Pods :: Fire";
    protected $hubs_pusher;

    protected function getHubName($hub)
    {
        $suffix = " (" . $hub->getId() . ")";
        if ($name = $hub->getName())
        {
            return $name . $suffix;
        }
        return "Hub" . $suffix;
    }

    public function __construct(HubsPusherInterface $hubs)
    {
        parent::__construct();
        $this -> hubs_pusher = $hubs;

        $open = function(HubsSocketInterface $hubs)
        {
            $name = $this->getHubName($hubs);
            $this->line("
                <info>" . $name . " connected.</info>
            ");
        };
        $this->hubs_pusher->getEmitter()->on("open", $open);

        $close = function(HubsSocketInterface $hub)
        {
            $name = $this->getHubName($hub);
            $this->line("
                <info>" . $name . " disconnected.</info>
            ");
        };
        $this->hubs_pusher->getEmitter()->on("close", $close);

        $message = function(HubsSocketInterface $hub, $message)
        {
            $name = $this->getHubName($hub);
            $this->line("
                <info>New message from " . $name . ":</info>
                <comment>" . $message . "</comment>
                <info>.</info>
            ");
        };
        $this->hubs_pusher->getEmitter()->on("message", $message);

        $name = function(HubsSocketInterface $hub, $message)
        {
            $this->line("
                <info>User changed their name to:</info>
                <comment>" . $message . "</comment>
                <info>.</info>
            ");
        };
        $this->hubs_pusher->getEmitter()->on("name", $name);

        $error = function(HubsSocketInterface $hub, $exception)
        {
            $message = $exception->getMessage();

            $this->line("
                <info>User encountered an exception:</info>
                <comment>" . $message . "</comment>
                <info>.</info>
            ");
        };
        $this->hubs_pusher->getEmitter()->on("error", $error);
    }

    public function fire()
    {
        $cfg = 'websocket.server-';
        $srvAddr = \Config::get($cfg.'def.addr', '0.0.0.0');
        $srvPort = \Config::get($cfg.'def.port', 16000);
        $zmqAddr = \Config::get($cfg.'zmq.addr', '127.0.0.1');
        $zmqPort = \Config::get($cfg.'zmq.port', 5555);
        $keepAlv = \Config::get($cfg.'def.keep-alive', 120);

        $port = (integer) $this->option("port");
        if ($port)
            $srvPort = $port;

        $loop = \React\EventLoop\Factory::create();

        $context = new \React\ZMQ\Context( $loop );
        $rep = $context -> getSocket(\ZMQ::SOCKET_PULL);
        $rep -> bind('tcp://'.$zmqAddr.':'.$zmqPort);
        $rep -> on('message', array($this -> hubs_pusher, 'onZmqMessage'));

        $socket = new \React\Socket\Server($loop);
        $socket->listen($srvPort, $srvAddr);
        new \Ratchet\Server\IoServer( $this -> hubs_pusher, $socket );
        $loop->addPeriodicTimer($keepAlv, function()
        {
            $sockets = $this->hubs_pusher->connections;
            foreach ($sockets as $current) {
                //$current->getSocket()->send(decbin(1));
            }
        });
        $loop->run();
    }

    protected function getOptions()
    {
        return [
            [
                "port",
                null,
                InputOption::VALUE_REQUIRED,
                "Port to listen on.",
                null
            ]
        ];
    }
}