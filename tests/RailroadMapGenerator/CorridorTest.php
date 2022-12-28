<?php

namespace App\Tests\RailroadMapGenerator;

use PHPUnit\Framework\TestCase;
use App\Service\Treasure\Treasure;
use App\Interface\TreasureInterface;
use App\Service\Map\Railroad\Corridor;

class CorridorTest extends TestCase
{
    /** @test */
    public function it_has_y_coordinate()
    {
        $corridor = Corridor::fromX(5);

        $this->assertIsNumeric($corridor->getX());
        $this->assertEquals(5, $corridor->getX());
    }

    /** @test */
    public function it_has_type()
    {
        $corridor = Corridor::fromX(5);

        $this->assertIsString($corridor->getType());
        $this->assertEquals(Corridor::TYPE, $corridor->getType());
    }

    /** @test */
    public function it_has_template()
    {
        $corridor = Corridor::fromX(5);

        $this->assertIsString($corridor->getTemplate());
        $this->assertFileExists("templates/".$corridor->getTemplate());
    }


    /** @test */
    public function it_has_treasure()
    {
        $corridor = Corridor::fromX(5);

        $this->assertNull($corridor->getTreasure());

        $treasure = new Treasure();
        $corridor->setTreasure($treasure);

        $this->assertInstanceOf(TreasureInterface::class, $corridor->getTreasure());
    }
}
