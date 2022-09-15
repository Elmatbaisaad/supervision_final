<?php

namespace App\Repository;

use Exception;
use ModbusTcpClient\Composer\Read\ReadCoilsBuilder;
use ModbusTcpClient\Network\NonBlockingClient;

class ReadCoil
{

    public $ip;
    public $unitId;
    public $connection;
    public $message;
    public $reponse_data; //recuperation de la valeur
    public $reponse_error; //recuperation de l'erreur s'il y'a

    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    public function setUnitId($unitId)
    {
        $this->unitId = $unitId;
    }

    public function setMessage($msg)
    {
        $this->message = $msg;
    }

    public function setConnection(int $adresse, string $name)
    {
        try {
            $this->connection = ReadCoilsBuilder::newReadCoils($this->ip)
                ->coil($adresse,$name)
                ->build();
            $this->setMessage('build succes');
        }catch (Exception $e){
            $this->setMessage('build erreur');
        }
    }

    public function getReponse()
    {
        $reponseContainer = (new NonBlockingClient(['readTimeoutSec' => 0.8]))->sendRequests($this->connection);
        $this->reponse_data =$reponseContainer->getData();
        $this->reponse_error = $reponseContainer->getErrors();
    }


}