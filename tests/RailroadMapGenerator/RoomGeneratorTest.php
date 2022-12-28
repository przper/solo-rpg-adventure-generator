<?php

namespace App\Tests\RailroadMapGenerator;

use App\Interface\TreasureInterface;
use App\Service\RailroadGenerator\Room;
use App\Service\RailroadGenerator\RoomGenerator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RoomGeneratorTest extends KernelTestCase
{
    private RoomGenerator $generator;

    public function setUp(): void
    {
        self::bootKernel();

        $container = static::getContainer();

        $this->generator = $container->get(RoomGenerator::class);
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
