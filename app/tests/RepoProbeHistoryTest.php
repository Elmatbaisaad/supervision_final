<?php

namespace App\Tests;
use App\Repository\RepoProbeHistory;
use PHPUnit\Framework\TestCase;


class RepoProbeHistoryTest extends TestCase
{

    /** @test  */
    public function verifying_that_whe_get_the_right_id()
    {
        $repoProbe = new RepoProbeHistory();
        $repoProbe->retrieveIdProbeFromConfigFile(1);
        $this->assertEquals($repoProbe->sonde_id,1);
        $this->assertEquals($repoProbe->nomSonde,'Sonde de Niveau');

    }






}