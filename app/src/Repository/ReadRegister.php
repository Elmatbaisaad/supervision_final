<?php

namespace App\Repository;

use Exception;
use ModbusTcpClient\Composer\Read\ReadRegistersBuilder;
use ModbusTcpClient\Network\NonBlockingClient;

class ReadRegister
{
    public $ip;
    public $unitID;
    public $fc3;
    public $message;
    public $reponse_data;//recuperation de la valeur
    public $reponse_error;//recuperation de l'erreur s'il y'a

    public function setIp($ip)
    {
        $this->ip = $ip;

    }
    public function setUnitID($unitID)
    {
        $this->unitID = $unitID;
    }

    public function setMessageDeRetour($msg){
        $this->message = $msg;
    }

    public function connection( $adresse,string $name)
    {
        try {
            $this->fc3 = ReadRegistersBuilder::newReadHoldingRegisters($this->ip,$this->unitID)
                ->int16($adresse,$name)
                ->build();
            $this->setMessageDeRetour('build succés');

        }catch (Exception $e){
            $this->setMessageDeRetour('build erreur');
        }
    }

    public function getResponse()
    {
        $reponseContainer = (new NonBlockingClient(['readTimeoutSec' => 0.2]))->sendRequests($this->fc3);
        $this->reponse_data =$reponseContainer->getData();
        $this->reponse_error = $reponseContainer->getErrors();

    }

}