<?php

namespace App\Tests\RailroadMapGenerator;

use PHPUnit\Framework\TestCase;
use App\Service\Treasure\Treasure;
use App\Interface\TreasureInterface;
use App\Service\Map\Railroad\Corridor;

class CorridorTest extends TestCase
{
    /** @test */
    public function it_has_x_and_y_coordinate()
    {
        $corridor = Corridor::fromCoordinates(0, 1);

        $this->assertIsNumeric($corridor->getXCoordinate());
        $this->assertEquals(0, $corridor->getXCoordinate());
        $this->assertIsNumeric($corridor->getYCoordinate());
        $this->assertEquals(1, $corridor->getYCoordinate());
    }

    /** @test */
    public function it_has_type()
    {
        $corridor = Corridor::fromCoordinates(0, 1);

        $this->assertIsString($corridor->getType());
        $this->assertEquals(Corridor::TYPE, $corridor->getType());
    }

    /** @test */
    public function it_has_treasure()
    {
        $corridor = Corridor::fromCoordinates(0, 1);

        $this->assertNull($corridor->getTreasure());

        $treasure = new Treasure();
        $corridor->setTreasure($treasure);

        $this->assertInstanceOf(TreasureInterface::class, $corridor->getTreasure());
    }
}
