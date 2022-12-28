<?php

namespace App\Tests\RailroadMapGenerator;

use App\Service\RailroadGenerator\Room;
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
}
