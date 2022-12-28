<?php

namespace App\Tests\RailroadMapGenerator;

use PHPUnit\Framework\TestCase;
use App\Service\Map\Railroad\Map;
use App\Service\Map\Railroad\Room;
use App\Service\Map\Railroad\Corridor;

class MapTest extends TestCase
{
    /** @test */
    public function it_has_cells()
    {
        $map = new Map();

        $this->assertIsArray($map->getCells());
        $this->assertCount(0, $map->getCells());
    }

    /** @test */
    public function room_can_be_added()
    {
        $map = new Map();
        $room = new Room();

        $map->addCell($room);

        $this->assertCount(1, $map->getCells());
        $this->assertCount(1, $map->getRooms());
        $this->assertInstanceOf(Room::class, $map->getRooms()[0]);
    }

    /** @test */
    public function corridor_can_be_added()
    {
        $map = new Map();
        $corridor = new Corridor();

        $map->addCell($corridor);

        $this->assertCount(1, $map->getCells());
        $this->assertCount(1, $map->getCorridors());
        $this->assertInstanceOf(Corridor::class, $map->getCorridors()[0]);
    }
}
