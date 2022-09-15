<?php

namespace App\Controller;

use App\Entity\HistoriqueAlarm;
use App\Repository\HistoriqueAlarmRepository;
use App\Repository\RepoHistoryAlarm;
use App\Repository\RepositoryAutomate;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StoreAlarmController extends AbstractController
{
    public function controle_alarm(string $idServeur,string $nom, RepositoryAutomate $repositoryAutomate, RepoHistoryAlarm $repoHistoryAlarm,
                                   HistoriqueAlarm $historiqueAlarm, EntityManagerInterface $em,HistoriqueAlarmRepository $historiqueAlarmRepository)
    {
        $historiqueRepo = $historiqueAlarmRepository->findByNullDateFin($repoHistoryAlarm->retrieveIdAlarm($nom));

        if ($repositoryAutomate->chercherValeurBobine($idServeur,$nom)==1 && count($historiqueRepo) == 1){
            $historiqueAlarm = $historiqueAlarmRepository->findOneBy(["id_alarm"=>$repoHistoryAlarm->retrieveIdAlarm($nom),"DateFin"=>null]);;
            $historiqueAlarm->setDateFin(new \DateTime());
            $repoHistoryAlarm->store($historiqueAlarm,$em);

        }
        if ( count($historiqueRepo) == 0 && $repositoryAutomate->chercherValeurBobine($idServeur,$nom) == 0){
                $historiqueAlarm->setDateDebut(new \DateTime());
                $historiqueAlarm->setMessage($repoHistoryAlarm->retreiveMessage($nom));
                $historiqueAlarm->setZone($repoHistoryAlarm->retrieveZone($nom));
                $historiqueAlarm->setLieu($repoHistoryAlarm->retrieveLieu($nom));
                $historiqueAlarm->setIdAlarm($repoHistoryAlarm->retrieveIdAlarm($nom));
                $repoHistoryAlarm->store($historiqueAlarm,$em);
        }



        elseif ($repositoryAutomate->chercherValeurBobine($idServeur,$nom) == 1 ){

        }




    }
    #[Route('/storealarm', name: 'storealarm')]
    public function index(EntityManagerInterface $em,HistoriqueAlarmRepository $historiqueAlarmRepository): Response
    {
        $repoAlarm = new RepoHistoryAlarm();
        $repoAutomate = new RepositoryAutomate();
        $alarmFiltreOn = new HistoriqueAlarm();
        $alarmOff = new HistoriqueAlarm();
        $alarmPumpOn = new HistoriqueAlarm();
        $this->controle_alarm('PR1','Alarm OFF',$repoAutomate,$repoAlarm,$alarmOff,$em,$historiqueAlarmRepository);
        $this->controle_alarm('PR1','Pump ON',$repoAutomate,$repoAlarm,$alarmPumpOn,$em,$historiqueAlarmRepository);
        $this->controle_alarm('PR1','Filtre ON',$repoAutomate,$repoAlarm,$alarmFiltreOn,$em,$historiqueAlarmRepository);
        $this->controle_alarm('PR2','Alarm OFF RAS 2',$repoAutomate,$repoAlarm,$alarmOff,$em,$historiqueAlarmRepository);
        $this->controle_alarm('PR2','Pump ON RAS 2',$repoAutomate,$repoAlarm,$alarmPumpOn,$em,$historiqueAlarmRepository);
        $this->controle_alarm('PR2','Filtre ON RAS 2',$repoAutomate,$repoAlarm,$alarmFiltreOn,$em,$historiqueAlarmRepository);
        $this->controle_alarm('AR1','Alarm pompe 1',$repoAutomate,$repoAlarm,$alarmOff,$em,$historiqueAlarmRepository);
        $this->controle_alarm('AR1','Alarm oxy ext',$repoAutomate,$repoAlarm,$alarmPumpOn,$em,$historiqueAlarmRepository);
        $this->controle_alarm('AR1','alarm oxy aval',$repoAutomate,$repoAlarm,$alarmFiltreOn,$em,$historiqueAlarmRepository);
        $historiquesAlarmes = $historiqueAlarmRepository->findAll();
        return $this->render('store_alarm/index.html.twig',[
            'historiquesA'=>$historiquesAlarmes
        ]);
    }
    /** @Route ("/jsonalarm", name="jsonalarm") */
    public function jsonAlarm(EntityManagerInterface $em,HistoriqueAlarmRepository $historiqueAlarmRepository):Response
    {
        $repoAlarm = new RepoHistoryAlarm();
        $repoAutomate = new RepositoryAutomate();
        $alarmFiltreOn = new HistoriqueAlarm();
        $alarmOff = new HistoriqueAlarm();
        $alarmPumpOn = new HistoriqueAlarm();
        $this->controle_alarm('PR1','Alarm OFF',$repoAutomate,$repoAlarm,$alarmOff,$em,$historiqueAlarmRepository);
        $this->controle_alarm('PR1','Pump ON',$repoAutomate,$repoAlarm,$alarmPumpOn,$em,$historiqueAlarmRepository);
        $this->controle_alarm('PR1','Filtre ON',$repoAutomate,$repoAlarm,$alarmFiltreOn,$em,$historiqueAlarmRepository);
        $this->controle_alarm('PR2','Alarm OFF RAS 2',$repoAutomate,$repoAlarm,$alarmOff,$em,$historiqueAlarmRepository);
        $this->controle_alarm('PR2','Pump ON RAS 2',$repoAutomate,$repoAlarm,$alarmPumpOn,$em,$historiqueAlarmRepository);
        $this->controle_alarm('PR2','Filtre ON RAS 2',$repoAutomate,$repoAlarm,$alarmFiltreOn,$em,$historiqueAlarmRepository);
        $this->controle_alarm('AR1','Alarm pompe 1',$repoAutomate,$repoAlarm,$alarmOff,$em,$historiqueAlarmRepository);
        $this->controle_alarm('AR1','Alarm oxy ext',$repoAutomate,$repoAlarm,$alarmPumpOn,$em,$historiqueAlarmRepository);
        $this->controle_alarm('AR1','alarm oxy aval',$repoAutomate,$repoAlarm,$alarmFiltreOn,$em,$historiqueAlarmRepository);
        $historiquesAlarmes = $historiqueAlarmRepository->findAll();
        return $this->json([
            'historiquesA'=>$historiquesAlarmes
        ]);


    }



}
