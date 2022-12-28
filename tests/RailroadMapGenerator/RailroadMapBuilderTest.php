<?php

namespace App\Tests\RailroadMapGenerator;

use PHPUnit\Framework\TestCase;
use App\Service\RailroadGenerator\Map;
use App\Service\RailroadGenerator\RailroadMapBuilder;

class RailroadMapBuilderTest extends TestCase
{
    private RailroadMapBuilder $builder;

    public function setUp(): void
    {
        parent::setUp();

        $this->builder = new RailroadMapBuilder();
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
        $this->builder->setMinCorridorLength(1);
        $this->builder->setMaxCorridorLength(3);

        /** @var Map $map */
        $map = $this->builder->create();

        $this->assertGreaterThanOrEqual(1, count($map->getCorridors()));
        $this->assertLessThanOrEqual(3, count($map->getCorridors()));
        $this->assertGreaterThan(2, $map->getLength());
        $this->assertLessThanOrEqual(5, $map->getLength());
    }
}
