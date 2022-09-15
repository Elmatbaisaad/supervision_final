<?php

namespace App\Controller;

use App\Repository\HistoriqueAlarmRepository;
use App\Repository\HistoriqueSondeValeurRepository;
use App\Repository\RepoProbeHistory;
use App\Repository\RepositoryAutomate;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Config\KnpPaginatorConfig;

class ReadregistersandcoilsController extends AbstractController
{
    public function conversion($nombre)
    {
        return $nombre / 100;
    }

    public function setSondeId(RepoProbeHistory $probeHistory, $id)
    {
        $probeHistory->sonde_id = $id;
    }


    #[Route('/', name: 'getValue')]
    public function index(HistoriqueAlarmRepository $historiqueAlarmRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $repo = new RepositoryAutomate();
        $historiquesAlarms = $historiqueAlarmRepository->findBy([], ['DateDebut' => 'desc']);
        /** @var  KnpPaginatorConfig */
        $historiquesAlarm = $paginator->paginate(
            $historiquesAlarms,
            $request->query->getInt('page', 1),
            5
        );
        return $this->render('readregistersandcoils/index.html.twig', [
            'repo' => $repo,
            'oxygen' => $this->conversion($repo->chercherValeurRegister('PR1', 'sonde Oxygene')),
            'oxygen_id' => $repo->sonde_id,
            'niveau' => $this->conversion($repo->chercherValeurRegister('PR1', 'Sonde de Niveau')),
            'niveau_id' => $repo->sonde_id,
            'pression' => $this->conversion($repo->chercherValeurRegister('PR1', 'Sonde de Pression')),
            'pression_id' => $repo->sonde_id,
            'f_on' => $repo->chercherValeurBobine('PR1', 'Filtre ON'),
            'message_filtre' => $repo->message,
            'a_off' => $repo->chercherValeurBobine('PR1', 'Alarm OFF'),
            'message_alarm' => $repo->message,
            'p_on' => $repo->chercherValeurBobine('PR1', 'Pump ON'),
            'message_pump' => $repo->message,
            'historiquesA' => $historiquesAlarm,
            'pompe1' => $repo->chercherValeurBobine('AR1', 'Alarm pompe 1'),
            'aoxy_ext' => $repo->chercherValeurBobine('AR1', 'Alarm oxy ext'),
            'aoxy_aval' => $repo->chercherValeurBobine('AR1', 'alarm oxy aval'),
            'a_off_ras2' => $repo->chercherValeurBobine('PR2', 'Alarm OFF RAS 2'),
        ]);
    }


    #[Route ("/JsonValue", name: "JsonValue")]
    public function jsonValue(): Response
    {
        $repo = new RepositoryAutomate();

        return $this->json([
            'oxygen' => $this->conversion($repo->chercherValeurRegister('PR1', 'sonde Oxygene')),
            'niveau' => $this->conversion($repo->chercherValeurRegister('PR1', 'Sonde de Niveau')),
            'pression' => $this->conversion($repo->chercherValeurRegister('PR1', 'Sonde de Pression')),
            'f_on' => $repo->chercherValeurBobine('PR1', 'Filtre ON'),
            'a_off' => $repo->chercherValeurBobine('PR1', 'Alarm OFF'),
            'p_on' => $repo->chercherValeurBobine('PR1', 'Pump ON'),
            'oxygen_ext' => $this->conversion($repo->chercherValeurRegister('AR1', 'oxy exterieur')),
            'oxygen_ras1' => $this->conversion($repo->chercherValeurRegister('PR1', 'sonde Oxygene')),
            'oxygen_ras2' => $this->conversion($repo->chercherValeurRegister('PR2', 'sonde Oxygene ras 2')),
            'niveau_ras2' => $this->conversion($repo->chercherValeurRegister('PR2', 'Sonde de Niveau ras 2')),
            'pression_ras2' => $this->conversion($repo->chercherValeurRegister('PR2', 'Sonde de Pression ras 2')),
            'niveau_alv' => $this->conversion($repo->chercherValeurRegister('AR1', 'niv bassin')),
            'temperature' => $this->conversion($repo->chercherValeurRegister('AR1', 'temperature')),
            'f_on_ras2' => $repo->chercherValeurBobine('PR2', 'Filtre ON RAS 2'),
            'a_off_ras2' => $repo->chercherValeurBobine('PR2', 'Alarm OFF RAS 2'),
            'p_on_ras2' => $repo->chercherValeurBobine('PR2', 'Pump ON RAS 2'),
            'oxygen_ras2p' => $this->conversion($repo->chercherValeurRegister('PR2', 'sonde Oxygene ras 2')),
            'niveau_ras2p' => $this->conversion($repo->chercherValeurRegister('PR2', 'Sonde de Niveau ras 2')),
            'pression_ras2p' => $this->conversion($repo->chercherValeurRegister('PR2', 'Sonde de Pression ras 2')),
            'pompe1' => $repo->chercherValeurBobine('AR1', 'Alarm pompe 1'),
            'aoxy_ext' => $repo->chercherValeurBobine('AR1', 'Alarm oxy ext'),
            'aoxy_aval' => $repo->chercherValeurBobine('AR1', 'alarm oxy aval'),
        ]);
    }


    /** @Route ("/storedataindatabase/{id}",name="findbyid") */
    public function showHistoryById(HistoriqueSondeValeurRepository $doctrine, $id): Response
    {
        $dateTime = new \DateTime();
        $histdate = [];
        $histidsonde = [];
        $histvaleur = [];

        $repoProb = new RepoProbeHistory();
        $historiques = $repoProb->filter($doctrine, $id, $dateTime);
        if ($repoProb) {
            $this->setSondeId($repoProb, $id);
        } else {
            return new Response("<html><body><h1>Id que vous cherchez n'existe pas</h1></body></html>");
        }
        foreach ($historiques as $hist) {
            $histdate[] = $hist->getDateEtHeure()->format('Y-m-d H:i');
            array_push($histidsonde, strval($repoProb->extractProbeNameFromIdProbeFromConfiguartionFile($hist)));
            $histvaleur[] = $this->conversion($hist->getValeur());
        }
        return $this->render('readregistersandcoils/showbyid.html.twig', [
            'historique' => $historiques,
            'date' => json_encode($histdate),
            'idsonde' => $histidsonde[0],
            'valeur' => json_encode($histvaleur),
            'repoprobeid' => $repoProb->sonde_id,
            'datetime' => $dateTime->format('Y-m-d'),
            'findbydate' => $repoProb->filter($doctrine, $id, $dateTime),
        ]);
    }


    /** @Route ("/jsonHistoryValue/{id}", name="jsonhistoryvalue") */ //Chartjs
    public function jsonHistoryValue(HistoriqueSondeValeurRepository $doctrine, $id, Request $request): Response
    {
        $dateTime = new \DateTime($request->get('date'));
        $histdate = [];
        $histidsonde = [];
        $histvaleur = [];
        $repoProb = new RepoProbeHistory();
        $historiques = $repoProb->filter($doctrine, $id, $dateTime);

        foreach ($historiques as $hist) {
            $histdate[] = $hist->getDateEtHeure()->format('Y-m-d H:i');
            array_push($histidsonde, strval($repoProb->extractProbeNameFromIdProbeFromConfiguartionFile($hist)));
            $histvaleur[] = $this->conversion($hist->getValeur());
        }

        return $this->json([
            'historique' => $historiques,
            'date' => $histdate,
            'idsonde' => $histidsonde[0],
            'valeur' => $histvaleur,
            'datetime' => $dateTime->format('d-m-Y')
        ]);
    }


    /** @Route ("/showalarmhistory", name="showalarmhistory") */
    public function showAlarmHistory(HistoriqueAlarmRepository $historiqueAlarmRepository, Request $request, PaginatorInterface $paginator)
    {
        $historiquesAlarms = $historiqueAlarmRepository->findBy([], ['DateDebut' => 'desc']);
        /** @var  KnpPaginatorConfig */
        $historiquesAlarm = $paginator->paginate(
            $historiquesAlarms,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('readregistersandcoils/showalarmhistory.html.twig', [
            'historiquesA' => $historiquesAlarm
        ]);
    }

    /** @Route ("/jsonshowalarm", name="jsonalarmhistory") */
    public function jsonAlarmHistory(HistoriqueAlarmRepository $historiqueAlarmRepository): Response
    {

        $historiquesAlarm = $historiqueAlarmRepository->findBy([], ['DateDebut' => 'desc']);

        return $this->json([
            'historiquesA' => $historiquesAlarm,

        ]);

    }

    /** @Route ("/historiquealarmdetail",name="historiquealarmdetail") */
    public function historiqueAlarmDetail(HistoriqueAlarmRepository $historiqueAlarmRepository)
    {
        $historiquesAlarm = $historiqueAlarmRepository->findBy([], ['DateDebut' => 'desc']);

        return $this->render('readregistersandcoils/historiquealarmdetail.html.twig', [
            'historiquesA' => $historiquesAlarm
        ]);


    }

    /** @Route("/pisciculture",name="pisciculture") */
    public function pisciculture(HistoriqueAlarmRepository $historiqueAlarmRepository, Request $request, PaginatorInterface $paginator)
    {
        $repo = new RepositoryAutomate();
        $historiquesAlarms = $historiqueAlarmRepository->findBy(array('zone' => 'Pisciculture'), array('DateDebut' => 'desc'));
        /** @var  KnpPaginatorConfig */
        $historiquesAlarm = $paginator->paginate(
            $historiquesAlarms,
            $request->query->getInt('page', 1),
            5
        );
        return $this->render('readregistersandcoils/pisciculture.html.twig', [
            'historiquesA' => $historiquesAlarm,
            'repo' => $repo,
            'oxygen_ras1p' => $this->conversion($repo->chercherValeurRegister('PR1', 'sonde Oxygene')),
            'oxygen_ras2p' => $this->conversion($repo->chercherValeurRegister('PR2', 'sonde Oxygene')),
        ]);
    }

    /** @Route ("/alevinage",name="alevinage") */
    public function alevinage(HistoriqueAlarmRepository $historiqueAlarmRepository, Request $request, PaginatorInterface $paginator)
    {
        $repo = new RepositoryAutomate();
        $historiquesAlarms = $historiqueAlarmRepository->findBy(array('zone' => 'Alevinage'), array('DateDebut' => 'desc'));
        /** @var  KnpPaginatorConfig */
        $historiquesAlarm = $paginator->paginate(
            $historiquesAlarms,
            $request->query->getInt('page', 1),
            5);
        return $this->render('readregistersandcoils/alevinage.html.twig', [
            'historiquesA' => $historiquesAlarm,
            'repo' => $repo,
            'oxygen_ext' => $this->conversion($repo->chercherValeurRegister('AR1', 'oxy exterieur')),
            'oxygen_id' => $repo->sonde_id,
        ]);

    }

    /** @Route ("/ras/{id}",name="ras") */
    public function ras1($id, HistoriqueAlarmRepository $historiqueAlarmRepository, Request $request, PaginatorInterface $paginator)
    {
        $repo = new RepositoryAutomate();
        $historiquesAlarms = $historiqueAlarmRepository->findBy(array('lieu' => $id, 'zone' => 'Pisciculture'), array('DateDebut' => 'desc'));
        /** @var  KnpPaginatorConfig */
        $historiquesAlarm = $paginator->paginate(
            $historiquesAlarms,
            $request->query->getInt('page', 1),
            5);
        return $this->render('readregistersandcoils/ras.html.twig', [
            'id' => $id,
            'historiquesA' => $historiquesAlarm,
            'repo' => $repo,
            'oxygen' => $this->conversion($repo->chercherValeurRegister('PR1', 'sonde Oxygene')),
            'oxygen_id' => $repo->sonde_id,
            'niveau' => $this->conversion($repo->chercherValeurRegister('PR1', 'Sonde de Niveau')),
            'niveau_id' => $repo->sonde_id,
            'pression' => $this->conversion($repo->chercherValeurRegister('PR1', 'Sonde de Pression')),
            'pression_id' => $repo->sonde_id,


            'oxygen_ras2' => $this->conversion($repo->chercherValeurRegister('PR2', 'sonde Oxygene ras 2')),
            'oxygen_id_ras2' => $repo->sonde_id,
            'niveau_ras2' => $this->conversion($repo->chercherValeurRegister('PR2', 'Sonde de Niveau ras 2')),
            'niveau_id_ras2' => $repo->sonde_id,
            'pression_ras2' => $this->conversion($repo->chercherValeurRegister('PR2', 'Sonde de Pression ras 2')),
            'pression_id_ras2' => $repo->sonde_id,


            'f_on' => $repo->chercherValeurBobine('PR1', 'Filtre ON'),
            'message_filtre' => $repo->message,
            'a_off' => $repo->chercherValeurBobine('PR1', 'Alarm OFF'),
            'message_alarm' => $repo->message,
            'p_on' => $repo->chercherValeurBobine('PR1', 'Pump ON'),
            'message_pump' => $repo->message,

            'f_on_ras2' => $repo->chercherValeurBobine('PR2', 'Filtre ON RAS 2'),
            'message_filtre_ras2' => $repo->message,
            'a_off_ras2' => $repo->chercherValeurBobine('PR2', 'Alarm OFF RAS 2'),
            'message_alarm_ras2' => $repo->message,
            'p_on_ras2' => $repo->chercherValeurBobine('PR2', 'Pump ON RAS 2'),
            'message_pump_ras2' => $repo->message,
        ]);

    }

    /** @Route ("/rasalevinage",name="rasalevinage") */
    public function rasAlevinage(HistoriqueAlarmRepository $historiqueAlarmRepository, Request $request, PaginatorInterface $paginator)
    {

        $repo = new RepositoryAutomate();
        $historiquesAlarms = $historiqueAlarmRepository->findBy(array('lieu' => 'RAS 1', 'zone' => 'Alevinage'), array('DateDebut' => 'desc'));
        /** @var  KnpPaginatorConfig */
        $historiquesAlarm = $paginator->paginate(
            $historiquesAlarms,
            $request->query->getInt('page', 1),
            5);
        return $this->render('readregistersandcoils/rasalevinage.html.twig', [
            'historiquesA' => $historiquesAlarm,
            'repo' => $repo,
            'oxygen_ext' => $this->conversion($repo->chercherValeurRegister('AR1', 'oxy exterieur')),
            'oxygen_ext_id' => $repo->sonde_id,
            'niveau' => $this->conversion($repo->chercherValeurRegister('AR1', 'niv bassin')),
            'niveau_id' => $repo->sonde_id,
            'temperature' => $this->conversion($repo->chercherValeurRegister('AR1', 'temperature')),
            'temperature_id' => $repo->sonde_id,

            'pompe1' => $repo->chercherValeurBobine('AR1', 'Alarm pompe 1'),
            'message_pompe1' => $repo->message,
            'aoxy_ext' => $repo->chercherValeurBobine('AR1', 'Alarm oxy ext'),
            'message_oxy_ext' => $repo->message,
            'aoxy_aval' => $repo->chercherValeurBobine('AR1', 'alarm oxy aval'),
            'message_oxy_aval' => $repo->message,

        ]);

    }


}
