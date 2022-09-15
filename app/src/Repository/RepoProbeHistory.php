<?php

namespace App\Repository;

use App\Entity\HistoriqueSondeValeur;
use Doctrine\ORM\EntityManagerInterface;


class RepoProbeHistory
{
    public $sonde_id;
    public $url_config_automae = '/config_client.json';
    public $filter;
    public $nomSonde;
    public $messageTest;
    public function getUrl($url)
    {
        return json_decode(file_get_contents($url));

    }

    public function retrieveIdProbeFromConfigFile($id){
        $decode = $this->getUrl(__DIR__ . $this->url_config_automae);
        for ($i=0;$i<count($decode->{'mot'});$i++){
            if ($id == $decode->{'mot'}[$i]->{'id'}){
                $this->sonde_id = $decode->{'mot'}[$i]->{'id'};
                $this->nomSonde = $decode->{'mot'}[$i]->{'nom'};
            }
        }
    }

    public function filter(HistoriqueSondeValeurRepository $doctrine,$id,$date)
    {
           return $doctrine->findByDate($id,$date);
    }



    public function storeHistory(HistoriqueSondeValeur $historiqueSondeValeur, EntityManagerInterface $em)
    {
            $em->persist($historiqueSondeValeur);
            $em->flush();
            $this->messageTest='historique stocké';
    }
    public function extractProbeNameFromIdProbeFromConfiguartionFile(HistoriqueSondeValeur $historiqueSondeValeur)
    {
        $decode = $this->getUrl(__DIR__ . $this->url_config_automae);
        for ($i=0;$i<count($decode->{'mot'});$i++){
            if ($historiqueSondeValeur->getIdSonde() == $decode->{'mot'}[$i]->{'id'}){
                return $decode->{'mot'}[$i]->{'nom'};
            }
        }

    }



}