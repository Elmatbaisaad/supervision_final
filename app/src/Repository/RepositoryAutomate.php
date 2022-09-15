<?php

namespace App\Repository;
use App\Entity\HistoriqueSondeValeur;

class RepositoryAutomate
{

    public $message;
    public $nomSonde;
    public $adrSonde;
    public $sonde_id;
    public $url_config_automae = '/config_client.json';
    public $url_config_message = '/conf_alarm.json';

    public function getUrl($url)
    {
        return json_decode(file_get_contents($url));

    }

    public function retrieveIpServeur( $idServeur)
    {
        $decode = $this->getUrl(__DIR__ . $this->url_config_automae);
        for ($i=0;$i<count($decode->{'serveur'});$i++){
            if ($idServeur == $decode->{'serveur'}[$i]->{'id'}){
                return $decode->{'serveur'}[$i]->{'ip'};
            }
        }
        return 'id de serveur non trouvé';
    }
    public function retrieveUnitIdServeur( $idServeur)
    {
        $decode = $this->getUrl(__DIR__ . $this->url_config_automae);
        for ($i=0;$i<count($decode->{'serveur'});$i++){
            if ($idServeur == $decode->{'serveur'}[$i]->{'id'}){
                return $decode->{'serveur'}[$i]->{'unitId'};
            }
        }
        return 'id de serveur non trouvé';
    }
    public function chercherValeurRegister( $idServeur,string $id)
    {

        $decode = $this->getUrl(__DIR__ . $this->url_config_automae);
        $register = new ReadRegister();
        $register->setIp($this->retrieveIpServeur($idServeur));
        $register->setUnitID($this->retrieveUnitIdServeur($idServeur));
        for ($i=0;$i<count($decode->{'mot'});$i++){
            $register->connection($decode->{'mot'}[$i]->{'adresse'},$decode->{'mot'}[$i]->{'nom'});
            $register->getResponse();
            if ($id == $decode->{'mot'}[$i]->{'nom'}){
                $this->nomSonde = $decode->{'mot'}[$i]->{'nom'};
                $this->adrSonde = $decode->{'mot'}[$i]->{'adresse'};
                $this->sonde_id = $decode->{'mot'}[$i]->{'id'};
                $valeurRegister= $register->reponse_data[$decode->{'mot'}[$i]->{'nom'}];
            }
        }
        return $valeurRegister;
    }

    public function chercherValeurBobine(string $idServeur,string $id)
    {
        $decode = $this->getUrl(__DIR__ . $this->url_config_automae);
        $conf_alarm =  $this->getUrl(__DIR__ . $this->url_config_message);


        $coil = new ReadCoil();
        $coil->setIp($this->retrieveIpServeur($idServeur));
        for ($i=0;$i<count($decode->{'bit'});$i++)
        {
            $coil->setConnection($decode->{'bit'}[$i]->{'adresse'},$decode->{'bit'}[$i]->{'nom'});
            $coil->getReponse();
            if ($id == $decode->{'bit'}[$i]->{'nom'} and $id == $conf_alarm->{'alarm'}[$i]->{'nom'})
            {
                $this->message = $conf_alarm->{'alarm'}[$i]->{'message'};
                if ( $coil->reponse_data[$decode->{'bit'}[$i]->{'nom'}]==false)
                {
                    $valeurCoil= 0;
                }else
                {
                    $valeurCoil= $coil->reponse_data[$decode->{'bit'}[$i]->{'nom'}];
                }
            }
        }
        return $valeurCoil;
    }



}