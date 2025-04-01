<?php

namespace App\Tests\Integration\MapBuilding\RailroadMapGenerator;

use App\Core\Helper\Coordinates;
use App\Core\Map\Room;
use App\MapBuilding\Railroad\RoomGenerator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RoomGeneratorTest extends KernelTestCase
{
    private RoomGenerator $generator;

    public function setUp(): void
    {
        $this->generator = static::getContainer()->get(RoomGenerator::class);
    }

    /** @test */
    public function it_generates_rooms()
    {
        $room = $this->generator->generate(Coordinates::fromIntegers(0, 0));

        $this->assertInstanceOf(Room::class, $room);
    }
}
