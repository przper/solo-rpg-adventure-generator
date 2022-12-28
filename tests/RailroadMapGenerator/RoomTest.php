<?php

namespace App\Tests\RailroadMapGenerator;

use App\Interface\TreasureInterface;
use App\Service\RailroadGenerator\Room;
use App\Service\Treasure\Treasure;
use PHPUnit\Framework\TestCase;

class RoomTest extends TestCase
{
    /** @test */
    public function it_has_y_coordinate()
    {
        $room = Room::fromX(5);

        $this->assertIsNumeric($room->getX());
        $this->assertEquals(5, $room->getX());
    }

    /** @test */
    public function it_has_type()
    {
        $room = Room::fromX(5);

        $this->assertIsString($room->getType());
        $this->assertEquals(Room::TYPE, $room->getType());
    }

    /** @test */
    public function it_has_template()
    {
        $room = Room::fromX(5);

        $this->assertIsString($room->getTemplate());
        $this->assertFileExists("templates/".$room->getTemplate());
    }

    /** @test */
    public function it_has_treasure()
    {
        $room = Room::fromX(5);

        $this->assertNull($room->getTreasure());

        $treasure = new Treasure();
        $room->setTreasure($treasure);

        $this->assertInstanceOf(TreasureInterface::class, $room->getTreasure());
    }
}
