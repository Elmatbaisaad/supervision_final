<?php

namespace App\Controller;

use App\Entity\HistoriqueSondeValeur;
use App\Repository\HistoriqueSondeValeurRepository;
use App\Repository\RepositoryAutomate;
use App\Repository\RepoProbeHistory;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class StoreDataInDatabaseController extends AbstractController
{

    public function creationRepoProbeHistory()
    {
        return new RepoProbeHistory();
    }

    public function creationHistoriqueSondeValeur()
    {
        return new HistoriqueSondeValeur();

    }

    public function creationRepoAutomate()
    {
        return new RepositoryAutomate();

    }

    public function insertionHistoriqueSondeValeur(HistoriqueSondeValeur $historiqueSondeValeur, RepositoryAutomate $repositoryAutomate, $ipAutomate, $nomSonde)
    {
        $historiqueSondeValeur->setDateEtHeure(new \DateTime());
        $historiqueSondeValeur->setValeur($repositoryAutomate->chercherValeurRegister($ipAutomate, $nomSonde));
        $historiqueSondeValeur->setIdSonde($repositoryAutomate->sonde_id);

    }


    #[Route('/storedataindatabase', name: 'app_store_data_in_database')]
    public function index(EntityManagerInterface $em, HistoriqueSondeValeurRepository $historiqueRepo): Response
    {
        $histdate = [];
        $histIdSonde = [];
        $histValeur = [];
        $repoProbe = $this->creationRepoProbeHistory();
        $repoAutomate = $this->creationRepoAutomate();
        $historique_oxygene = $this->creationHistoriqueSondeValeur();
        $historique_niveau = $this->creationHistoriqueSondeValeur();
        $historique_pression = $this->creationHistoriqueSondeValeur();
        $historique_sonde_ext = $this->creationHistoriqueSondeValeur();
        $historique_sonde_temp = $this->creationHistoriqueSondeValeur();
        $historique_sonde_niv_alv = $this->creationHistoriqueSondeValeur();
        $historique_sonde_oxy_ras2 = $this->creationHistoriqueSondeValeur();
        $historique_sonde_pre_ras2 = $this->creationHistoriqueSondeValeur();
        $historique_sonde_niv_ras2 = $this->creationHistoriqueSondeValeur();
        $this->insertionHistoriqueSondeValeur($historique_oxygene, $repoAutomate, 'PR1', 'sonde Oxygene');
        $this->insertionHistoriqueSondeValeur($historique_niveau, $repoAutomate, 'PR1', 'Sonde de Niveau');
        $this->insertionHistoriqueSondeValeur($historique_pression, $repoAutomate, 'PR1', 'Sonde de Pression');
        $this->insertionHistoriqueSondeValeur($historique_sonde_oxy_ras2, $repoAutomate, 'PR2', 'sonde Oxygene ras 2');
        $this->insertionHistoriqueSondeValeur($historique_sonde_niv_ras2, $repoAutomate, 'PR2', 'Sonde de Niveau ras 2');
        $this->insertionHistoriqueSondeValeur($historique_sonde_pre_ras2, $repoAutomate, 'PR2', 'Sonde de Pression ras 2');
        $this->insertionHistoriqueSondeValeur($historique_sonde_ext, $repoAutomate, 'AR1', 'oxy exterieur');
        $this->insertionHistoriqueSondeValeur($historique_sonde_temp, $repoAutomate, 'AR1', 'temperature');
        $this->insertionHistoriqueSondeValeur($historique_sonde_niv_alv, $repoAutomate, 'AR1', 'niv bassin');


        $repoProbe->storeHistory($historique_oxygene, $em);
        $repoProbe->storeHistory($historique_niveau, $em);
        $repoProbe->storeHistory($historique_pression, $em);
        $repoProbe->storeHistory($historique_sonde_oxy_ras2, $em);
        $repoProbe->storeHistory($historique_sonde_pre_ras2, $em);
        $repoProbe->storeHistory($historique_sonde_niv_ras2, $em);
        $repoProbe->storeHistory($historique_sonde_ext, $em);
        $repoProbe->storeHistory($historique_sonde_temp, $em);
        $repoProbe->storeHistory($historique_sonde_niv_alv, $em);


        $historiques = $historiqueRepo->findAll();
        foreach ($historiques as $hist) {
            $histdate[] = $hist->getDateEtHeure();
            $histIdSonde[] = $hist->getIdSonde();
            $histValeur[] = $hist->getValeur();

        }
        return $this->render('store_data_in_database/index.html.twig', [
            'historique' => $historiques,
            'histdate' => json_encode($histdate),
            'histidsonde' => json_encode($histIdSonde),
            'histvaleur' => json_encode($histValeur)


        ]);
    }

    /** @Route ("/jsonHistory",name="jsonHistory") */
    public function jsonHistory(ManagerRegistry $doctrine, EntityManagerInterface $em): Response
    {

        $repoProbe = $this->creationRepoProbeHistory();
        $repoAutomate = $this->creationRepoAutomate();
        $historique_oxygene = $this->creationHistoriqueSondeValeur();
        $historique_niveau = $this->creationHistoriqueSondeValeur();
        $historique_pression = $this->creationHistoriqueSondeValeur();
        $historique_sonde_ext = $this->creationHistoriqueSondeValeur();
        $historique_sonde_temp = $this->creationHistoriqueSondeValeur();
        $historique_sonde_niv_alv = $this->creationHistoriqueSondeValeur();
        $historique_sonde_oxy_ras2 = $this->creationHistoriqueSondeValeur();
        $historique_sonde_pre_ras2 = $this->creationHistoriqueSondeValeur();
        $historique_sonde_niv_ras2 = $this->creationHistoriqueSondeValeur();
        $this->insertionHistoriqueSondeValeur($historique_oxygene, $repoAutomate, 'PR1', 'sonde Oxygene');
        $this->insertionHistoriqueSondeValeur($historique_niveau, $repoAutomate, 'PR1', 'Sonde de Niveau');
        $this->insertionHistoriqueSondeValeur($historique_pression, $repoAutomate, 'PR1', 'Sonde de Pression');
        $this->insertionHistoriqueSondeValeur($historique_sonde_oxy_ras2, $repoAutomate, 'PR2', 'sonde Oxygene ras 2');
        $this->insertionHistoriqueSondeValeur($historique_sonde_niv_ras2, $repoAutomate, 'PR2', 'Sonde de Niveau ras 2');
        $this->insertionHistoriqueSondeValeur($historique_sonde_pre_ras2, $repoAutomate, 'PR2', 'Sonde de Pression ras 2');
        $this->insertionHistoriqueSondeValeur($historique_sonde_ext, $repoAutomate, 'AR1', 'oxy exterieur');
        $this->insertionHistoriqueSondeValeur($historique_sonde_temp, $repoAutomate, 'AR1', 'temperature');
        $this->insertionHistoriqueSondeValeur($historique_sonde_niv_alv, $repoAutomate, 'AR1', 'niv bassin');

        $repoProbe->storeHistory($historique_oxygene, $em);
        $repoProbe->storeHistory($historique_niveau, $em);
        $repoProbe->storeHistory($historique_pression, $em);
        $repoProbe->storeHistory($historique_sonde_oxy_ras2, $em);
        $repoProbe->storeHistory($historique_sonde_pre_ras2, $em);
        $repoProbe->storeHistory($historique_sonde_niv_ras2, $em);
        $repoProbe->storeHistory($historique_sonde_ext, $em);
        $repoProbe->storeHistory($historique_sonde_temp, $em);
        $repoProbe->storeHistory($historique_sonde_niv_alv, $em);
        $historiquedoc = $doctrine->getRepository(HistoriqueSondeValeur::class);
        $historiques = $historiquedoc->findAll();
        return $this->json([
            'historique' => $historiques,

        ]);

    }
}


















