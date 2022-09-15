<?php
namespace App\Tests;
use App\Repository\ReadRegister;
use PHPUnit\Framework\TestCase;

class ReadRegistersTest extends TestCase
{
    protected $conn;
    protected function setUp():void
    {
        $this->conn = new ReadRegister();
        $this->conn->setIp('tcp://172.25.0.3:532');
        $this->conn->setUnitID(0);
    }

    /** @test */
    public function creating_ip_and_unit_id()
    {
        $this->assertEquals($this->conn->ip,'tcp://172.25.0.3:532');
        $this->assertEquals($this->conn->unitID,0);
    }

    /** @test */
    public function creating_connection()
    {
        $this->conn->connection(2,'my_register');
        $this->assertEquals($this->conn->message,'build succés');
    }

    /** @test */
    public function receiving_the_right_value_of_register()
    {
        $this->conn->connection(2,'Sonde de Pression');
        $this->conn->getResponse();
        $this->assertArrayHasKey('Sonde de Pression',$this->conn->reponse_data);
        $this->assertEquals($this->conn->reponse_data['Sonde de Pression'],216);
    }

    /** @test */
    public function building_is_failed()
    {
        $build = new ReadRegister();
        $build->setUnitID(0);
        $build->connection(10,'cc');
        $this->assertEquals($build->message,'build erreur');

    }


}