<?php

namespace App\Tests;

use App\Entity\HistoriqueAlarm;
use App\Repository\HistoriqueAlarmRepository;
use App\Repository\RepoHistoryAlarm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class HistoryAlarmTest extends KernelTestCase
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
    public function retreiving_right_message_from_configuration_file()
    {
        $repoHistoryAlarm = new RepoHistoryAlarm();
        $retrievedMessage = $repoHistoryAlarm->retreiveMessage('Alarm OFF');
        $this->assertEquals('Le niveau d\'eau est bas',$retrievedMessage);
        $errorTest = $repoHistoryAlarm->retreiveMessage('notFound');
        $this->assertEquals('nom d\'alarm nom trouvé',$errorTest);
    }

    /** @test */
    public function retreiving_right_lieu_from_configuration_file()
    {
        $repoHistoryAlarm = new RepoHistoryAlarm();
        $retrievedLieu = $repoHistoryAlarm ->retrieveLieu('Alarm OFF');
        $this->assertEquals('RAS 1',$retrievedLieu);
        $errorTest = $repoHistoryAlarm->retrieveLieu('notFound');
        $this->assertEquals('nom d\'alarm nom trouvé',$errorTest);
    }

    /** @test */
    public function retreiving_right_zone_from_configuration_file()
    {
        $repoHistoryAlarm = new RepoHistoryAlarm();
        $retrievedZone = $repoHistoryAlarm ->retrieveZone('Alarm OFF');
        $this->assertEquals('Pisciculture',$retrievedZone);
        $errorTest = $repoHistoryAlarm->retrieveZone('notFound');
        $this->assertEquals('nom d\'alarm nom trouvé',$errorTest);

    }
    /** @test */
    public function retreiving_right_idSonde_from_configuration_file()
    {
        $repoHistoryAlarm = new RepoHistoryAlarm();
        $retrievedAlarm = $repoHistoryAlarm ->retrieveIdAlarm('Alarm OFF');
        $this->assertEquals(0,$retrievedAlarm);
        $errorTest = $repoHistoryAlarm->retrieveIdAlarm('notFound');
        $this->assertEquals('nom d\'alarm nom trouvé',$errorTest);

    }

    /** @test */
    public function a_history_alarm_can_be_stored()
    {
        $datetime = new \DateTime();
        $repoHistoryAlarm = new RepoHistoryAlarm();
        $historyAlarm = new HistoriqueAlarm();
        $historyAlarm->setDateDebut(new \DateTime());
        $historyAlarm->setDateFin(new \DateTime());
        $historyAlarm->setMessage($repoHistoryAlarm->retreiveMessage('Alarm OFF'));
        $historyAlarm->setLieu($repoHistoryAlarm->retrieveLieu('Alarm OFF'));
        $historyAlarm->setZone($repoHistoryAlarm->retrieveZone('Alarm OFF'));
        $historyAlarm->setIdAlarm($repoHistoryAlarm->retrieveIdAlarm('Alarm OFF'));
        $repoHistoryAlarm->store($historyAlarm,$this->entitymanager);
        $historyRepo = $this->entitymanager->getRepository(HistoriqueAlarm::class);
        $historyFinder = $historyRepo->findOneBy(['message'=>'Le niveau d\'eau est bas']);
        $this->assertEquals($datetime->format('Y-m-d'),$historyFinder->getDateDebut()->format('Y-m-d'));
        $this->assertEquals($datetime->format('Y-m-d'),$historyFinder->getDateFin()->format('Y-m-d'));
        $this->assertEquals('Le niveau d\'eau est bas',$historyFinder->getMessage());
    }

    /** @test */
    public function filter_by_date_work()
    {
        $dateDebut = new \DateTime('2022-06-02');
        $repoHistoryAlarm = new RepoHistoryAlarm();
        $alarmArrayTest = array("id"=>1,"dateDebut"=>'2022-06-02',"dateFin"=>'2022-06-02',"message"=>'message',"lieu"=>'Ras 1',"zone"=>'Pisciculture',"id_sonde"=>0);
        $alarmRepoMock = $this->createMock(HistoriqueAlarmRepository::class);
        $alarmRepoMock->method('findByDateAlarm')->willReturn($alarmArrayTest);
        $result = $repoHistoryAlarm->filterByDate($alarmRepoMock,$dateDebut);
        $this->assertEquals($alarmArrayTest,$result);
    }




}