<?php

namespace App\Tests\Unit\RailroadMapGenerator;

use App\Helper\Coordinates;
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
    public function it_can_retrieve_cell_at_given_coordinates()
    {
        $map = new Map();

        $map->addCell(new Room());
        $map->addCell(new Corridor());
        $map->addCell(new Room());

        $this->assertInstanceOf(Room::class, $map->getCell(Coordinates::fromIntegers(0, 0)));
        $this->assertInstanceOf(Corridor::class, $map->getCell(Coordinates::fromIntegers(1, 0)));
        $this->assertInstanceOf(Room::class, $map->getCell(Coordinates::fromIntegers(2, 0)));

    }

    /** @test */
    public function room_can_be_added()
    {
        $map = new Map();
        $map->addCell(new Room());
        $map->addCell(new Room());

        $this->assertCount(2, $map->getCells());
        $this->assertCount(1, $map->getCells()[0]);
        $this->assertCount(2, $map->getRooms());
        $this->assertInstanceOf(Room::class, $map->getCells()[0][0]);
        $this->assertInstanceOf(Room::class, $map->getRooms()[0]);
    }

    /** @test */
    public function corridor_can_be_added()
    {
        $map = new Map();

        $map->addCell(new Corridor());
        $map->addCell(new Corridor());

        $this->assertCount(2, $map->getCells());
        $this->assertCount(1, $map->getCells()[0]);
        $this->assertCount(2, $map->getCorridors());
        $this->assertInstanceOf(Corridor::class, $map->getCells()[0][0]);
        $this->assertInstanceOf(Corridor::class, $map->getCorridors()[0]);
    }
}
