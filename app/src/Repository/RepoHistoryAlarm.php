<?php

namespace App\Repository;


use App\Entity\HistoriqueAlarm;
use Doctrine\ORM\EntityManagerInterface;

class RepoHistoryAlarm
{
    public $url_config_alarm = '/conf_alarm.json';


    public function getUrl($url)
    {
        return json_decode(file_get_contents($url));

    }

    public function retreiveMessage(string $alarm)
    {
        $decode = $this->getUrl(__DIR__ . $this->url_config_alarm);
        for ($i=0;$i<count($decode->{'alarm'});$i++){
            if ($alarm == $decode->{'alarm'}[$i]->{'nom'}){
                return $decode->{'alarm'}[$i]->{'message'};
            }
        }
        return 'nom d\'alarm nom trouvé';
    }
    public function store(HistoriqueAlarm $historiqueAlarm, EntityManagerInterface $em)
    {
        $em->persist($historiqueAlarm);
        $em->flush();
    }

    public function filterByDate(HistoriqueAlarmRepository $alarmRepo, \DateTime $dateDebut)
    {
        return $alarmRepo->findByDateAlarm($dateDebut);

    }

    public function retrieveLieu(string $alarm)
    {
        $decode = $this->getUrl(__DIR__ . $this->url_config_alarm);
        for ($i=0;$i<count($decode->{'alarm'});$i++){
            if ($alarm == $decode->{'alarm'}[$i]->{'nom'}){
                return $decode->{'alarm'}[$i]->{'lieu'};
            }
        }
        return 'nom d\'alarm nom trouvé';
    }

    public function retrieveZone(string $alarm)
    {
        $decode = $this->getUrl(__DIR__ . $this->url_config_alarm);
        for ($i=0;$i<count($decode->{'alarm'});$i++){
            if ($alarm == $decode->{'alarm'}[$i]->{'nom'}){
                return $decode->{'alarm'}[$i]->{'zone'};
            }
        }
        return 'nom d\'alarm nom trouvé';
    }
    public function retrieveIdAlarm(string $alarm)
    {
        $decode = $this->getUrl(__DIR__ . $this->url_config_alarm);
        for ($i=0;$i<count($decode->{'alarm'});$i++){
            if ($alarm == $decode->{'alarm'}[$i]->{'nom'}){
                return $decode->{'alarm'}[$i]->{'id'};
            }
        }
        return 'nom d\'alarm nom trouvé';
    }
}