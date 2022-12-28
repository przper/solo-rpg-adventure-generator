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
