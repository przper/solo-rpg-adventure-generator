<?php

namespace App\Tests\Unit\RailroadMapGenerator;

use App\Helper\Coordinates;
use PHPUnit\Framework\TestCase;
use App\Interface\TreasureInterface;
use App\Service\Map\Railroad\Corridor;
use App\Tests\Unit\RailroadMapGenerator\Fixtures\DummyTreasure;

class CorridorTest extends TestCase
{
    /** @test */
    public function it_has_x_and_y_coordinate()
    {
        $corridor = Corridor::fromCoordinates(Coordinates::fromIntegers(0, 1));

        $this->assertInstanceOf(Coordinates::class, $corridor->getCoordinates());
        $this->assertEquals(
            Coordinates::fromIntegers(0, 1),
            $corridor->getCoordinates()
        );
    }

    /** @test */
    public function it_has_type()
    {
        $corridor = Corridor::fromCoordinates(Coordinates::fromIntegers(0, 1));

        $this->assertIsString($corridor->getType());
        $this->assertEquals(Corridor::TYPE, $corridor->getType());
    }

    /** @test */
    public function it_has_treasure()
    {
        $corridor = Corridor::fromCoordinates(Coordinates::fromIntegers(0, 1));

        $this->assertNull($corridor->getTreasure());

        $corridor->setTreasure(new DummyTreasure());

        $this->assertInstanceOf(TreasureInterface::class, $corridor->getTreasure());
    }
}
