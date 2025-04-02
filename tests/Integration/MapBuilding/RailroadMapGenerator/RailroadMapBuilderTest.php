<?php

namespace App\Tests\Integration\MapBuilding\RailroadMapGenerator;

use App\Core\Map\Map;
use App\Core\Map\TileType;
use App\MapBuilding\Railroad\RailroadMapBuilder;
use App\Tests\DebugsMap;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RailroadMapBuilderTest extends KernelTestCase
{
    use DebugsMap;

    private RailroadMapBuilder $builder;

    public function setUp(): void
    {
        $this->builder = static::getContainer()->get(RailroadMapBuilder::class);
    }

    /** @test */
    public function it_builds_map()
    {
        $map = $this->builder->build();

        $this->assertInstanceOf(Map::class, $map);
    }

    /** @test */
    public function rooms_counter_can_be_set()
    {
        $map = $this->builder->setMaxRoomsCount(5)->build();

        $this->assertEquals(5, count($map->getTilesByType(TileType::Room)));
    }

    /** @test */
    public function corridors_length_range_can_be_set()
    {
        $map = $this
            ->builder
            ->setMaxRoomsCount(2)
            ->setMinCorridorLength(2)
            ->setMaxCorridorLength(5)
            ->build();

        $this->assertGreaterThanOrEqual(2, count($map->getTilesByType(TileType::Corridor)));
        $this->assertLessThanOrEqual(5, count($map->getTilesByType(TileType::Corridor)));
        $this->assertGreaterThanOrEqual(4, count($map->getTilesByType(...TileType::cases())));
        $this->assertLessThanOrEqual(7, count($map->getTilesByType(...TileType::cases())));
    }
}
