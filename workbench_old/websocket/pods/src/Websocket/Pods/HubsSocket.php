<?php
namespace Websocket\Pods;
use Ratchet\ConnectionInterface;
class HubsSocket
    implements HubsSocketInterface
{
    protected $socket;
    public function getSocket()
    {
        return $this -> socket;
    }
    public function setSocket(ConnectionInterface $socket)
    {
        $this -> socket = $socket;
        return $this;
    }
    public function getId()
    {
        return $this->id;
    }
    public function setId($id)
    {
        $this -> id = $id;
        return $this;
    }
    public function getAddress()
    {
        return $this->address;
    }
    public function setAddress($address)
    {
        $this -> address = $address;
        return $this;
    }

    public function saveHub($online = 1)
    {
        if( $online == 1 ){
            //$record = \Model\HubsDevices :: whereResourceId($this -> getId()) -> whereIp($this -> getAddress()) -> whereNull('identifier') -> first();
            $record = \Model\HubsDevices :: whereResourceId($this -> getId()) -> whereNull('identifier') -> orderBy('id','DESC') -> first();
            if(!$record){
                $hub = new \Model\HubsDevices();
                $hub -> resource_id = $this -> getId();
                $hub -> ip = $this -> getAddress();
                $hub -> online = 1;
                return $hub -> save();
            }
            else{
                $record -> ip = $this -> getAddress();
                $record -> online = 1;
                $record -> updated_at = \Carbon::now();
                return $record -> update();
            }
        }
        else{
            $hub = $this -> getHub();
            $hub -> online  = 0;
            $this-> updated_at = \Carbon::now();
            $hub -> update();
        }
    }

    public function getHub()
    {
        $ip = $this -> getAddress();
        $id = $this -> getId();
        return \Model\HubsDevices :: whereResourceId($id) -> whereIp($ip) -> orderBy('id','DESC') -> first();
    }
}