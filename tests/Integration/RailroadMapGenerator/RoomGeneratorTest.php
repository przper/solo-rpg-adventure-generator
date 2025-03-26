<?php

namespace App\Tests\Integration\RailroadMapGenerator;

use App\Helper\Coordinates;
use App\Service\Map\Core\Room;
use App\Service\Map\Railroad\RoomGenerator;
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
