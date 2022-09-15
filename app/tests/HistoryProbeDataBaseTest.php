<?php

namespace App\Tests;
use App\Entity\HistoriqueSondeValeur;
use App\Repository\HistoriqueSondeValeurRepository;
use App\Repository\RepoProbeHistory;
use App\Repository\RepositoryAutomate;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class HistoryProbeDataBaseTest extends KernelTestCase
{
    /** @var EntityManagerInterface */
    private $entitymanager;

    protected function setUp():void
    {
        $kernel = self::bootKernel();
        DatabasePrimer::prime($kernel);
        $this->entitymanager = $kernel->getContainer()->get('doctrine')->getManager();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entitymanager->close();
        $this->entitymanager = null;

    }
    /** @test  */
    public function test_works()
    {
        $this->assertTrue(true);
    }

    /** @test */
    public function a_history_probe_can_be_created_in_database()
    {
        $datetime = new \DateTime();
        $repoProbe = new RepoProbeHistory();
        $history = new HistoriqueSondeValeur();
        $history->setIdSonde(0);
        $history->setValeur(100);
        $history->setDateEtHeure(new \DateTime());
        $repoProbe->storeHistory($history,$this->entitymanager);
        $historyRepo = $this->entitymanager->getRepository(HistoriqueSondeValeur::class);
        $historyFilter =  $historyRepo->findOneBy(['valeur'=>100]);
        $this->assertEquals(0,$historyFilter->getIdSonde());
        $this->assertEquals($datetime->format('Y-m-d'),$historyFilter->getDateEtHeure()->format('Y-m-d'));
    }

    /** @test */
    public function filter_by_date_function_works_correctly()
    {
        $datetime = new \DateTime('2022-05-20');
        $historyRepo = $this->entitymanager->getRepository(HistoriqueSondeValeur::class);
        $historyFilter =  $historyRepo->findByDate(0,$datetime);
        $this->assertIsArray($historyFilter);

    }

    /** @test */
    public function filter_works()
    {
        $dateTime = new \DateTime('2022-05-30');
        $repoProbe = new RepoProbeHistory();
        $arrayTest = array("id"=>1,"date"=>'2022-05-30',"valeur"=>950,"idSonde"=>0);
        $historyRepoMock = $this->createMock(HistoriqueSondeValeurRepository::class);
        $historyRepoMock->method('findByDate')->willReturn($arrayTest);
        $result = $repoProbe->filter($historyRepoMock,0,$dateTime);
        $this->assertEquals($arrayTest,$result);

    }
    public function insertionHistoriqueSondeValeur(HistoriqueSondeValeur $historiqueSondeValeur,$valeur,$idsonde)
    {
        $historiqueSondeValeur->setDateEtHeure(new \DateTime());
        $historiqueSondeValeur->setValeur($valeur);
        $historiqueSondeValeur->setIdSonde($idsonde);

    }

    /** @test  */
    public function insertion_function_works()
    {
        $historique = new HistoriqueSondeValeur();
        $this->insertionHistoriqueSondeValeur($historique,10,1);
        $this->assertEquals($historique->getIdSonde(),1);
}

}