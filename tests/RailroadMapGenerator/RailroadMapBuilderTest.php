<?php

namespace App\Tests\RailroadMapGenerator;

use App\Interface\MapInterface;
use App\Service\Map\Railroad\Map;
use App\Service\Map\Railroad\RailroadMapBuilder;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RailroadMapBuilderTest extends KernelTestCase
{
    private RailroadMapBuilder $builder;

    public function setUp(): void
    {
        self::bootKernel();

        $container = static::getContainer();

        $this->builder = $container->get(RailroadMapBuilder::class);
    }

    /** @test */
    public function it_builds_map()
    {
        $map = $this->builder->setRoomsCount(1)->create();

        $this->assertInstanceOf(MapInterface::class, $map);
    }

    /** @test */
    public function rooms_counter_can_be_set()
    {
        $this->builder->setRoomsCount(5);

        /** @var Map $map */
        $map = $this->builder->create();

        $this->assertEquals(5, count($map->getRooms()));
    }

    /** @test */
    public function corridors_length_range_can_be_set()
    {
        $this->builder->setRoomsCount(2);
        $this->builder->setMinCorridorLength(2);
        $this->builder->setMaxCorridorLength(5);

        /** @var Map $map */
        $map = $this->builder->create();

        $this->assertGreaterThanOrEqual(2, count($map->getCorridors()));
        $this->assertLessThanOrEqual(5, count($map->getCorridors()));
        $this->assertGreaterThanOrEqual(4, $map->getLength());
        $this->assertLessThanOrEqual(7, $map->getLength());
    }
}
