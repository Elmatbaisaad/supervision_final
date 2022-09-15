<?php

namespace App\Tests;
use App\Repository\RepositoryAutomate;
use PHPUnit\Framework\TestCase;

class RepoTest extends TestCase
{
    /** @test */
    public function returning_the_value_of_register()
    {
        $repo = new RepositoryAutomate();
        $valeur = $repo->chercherValeurRegister('PR1','sonde Oxygene');
        $this->assertEquals($valeur,990);
    }

    /** @test */
    public function returning_the_value_of_coil()
    {
        $repo = new RepositoryAutomate();
        $valeur = $repo->chercherValeurBobine('PR1','Alarm OFF');
        $this->assertEquals($valeur,true);
        $valeur =$repo->chercherValeurBobine('PR1','Filtre ON');
        $this->assertEquals($valeur,false);
    }

    /** @test */
    public function verifying_the_message_alarm()
    {
        $repo = new RepositoryAutomate();
        $valeur = $repo->chercherValeurBobine('PR1','Alarm OFF');
        $this->assertEquals($repo->message,'Le niveau d\'eau est bas');

    }

    /** @test */
    public function verifying_name_and_adresse_of_register()
    {
        $repo = new RepositoryAutomate();
        $repo->chercherValeurRegister('PR1','sonde Oxygene');
        $this->assertEquals($repo->nomSonde,'sonde Oxygene');
        $this->assertEquals($repo->adrSonde,0);
        $this->assertEquals($repo->sonde_id,0);

    }
    /** @test */
    public function idTest()
    {
        $repo = new RepositoryAutomate();
        $repo->chercherValeurRegister('PR1','sonde Oxygene');
        $this->assertEquals($repo->sonde_id,0);

    }
    /** @test */
    public function returning_the_right_ip_adress()
    {
        $repo = new RepositoryAutomate();
        $ip=$repo->retrieveIpServeur('PR2');
        $this->assertEquals($ip,"tcp://192.36.39.170:502");


    }
    /** @test */
    public function returning_the_right_uid()
    {
        $repo = new RepositoryAutomate();
        $ip=$repo->retrieveUnitIdServeur('AR1');
        $this->assertEquals($ip,0);


    }

}