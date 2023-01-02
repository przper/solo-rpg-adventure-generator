<?php

namespace App\Tests\Integration\RailroadMapGenerator;

use App\Service\Map\Railroad\Room;
use App\Interface\TreasureInterface;
use App\Service\Map\Railroad\RoomGenerator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RoomGeneratorTest extends KernelTestCase
{
    private RoomGenerator $generator;

    public function setUp(): void
    {
        self::bootKernel();

        $this->generator = static::getContainer()->get(RoomGenerator::class);
    }

    /** @test */
    public function it_generates_rooms()
    {
        $room = $this->generator->generate();

        $this->assertInstanceOf(Room::class, $room);
    }

    /** @test */
    public function treasureChanceCanBeSet()
    {
        $this->generator->setTreasurePercentageChangeInInt(100);
        $room = $this->generator->generate();
        $this->assertInstanceOf(TreasureInterface::class, $room->getTreasure());

        $this->generator->setTreasurePercentageChangeInInt(0);
        $room = $this->generator->generate();
        $this->assertNull($room->getTreasure());
    }
}
