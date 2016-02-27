<?php
namespace Websocket\Pods;
use Evenement\EventEmitterInterface;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
interface HubsPusherInterface
    extends MessageComponentInterface
{
    public function getHubBySocket(ConnectionInterface $conn);
    public function getEmitter();
    public function setEmitter(EventEmitterInterface $emitter);
    public function getHubs();
}