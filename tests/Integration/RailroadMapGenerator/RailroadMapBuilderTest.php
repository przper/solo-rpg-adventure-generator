<?php

namespace App\Tests\Integration\RailroadMapGenerator;

use App\Service\Map\Core\Map;
use App\Service\Map\Core\TileTypes;
use App\Service\Map\Railroad\RailroadMapBuilder;
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
        $map = $this->builder->create();

        $this->assertInstanceOf(Map::class, $map);
    }

    /** @test */
    public function rooms_counter_can_be_set()
    {
        $map = $this->builder->setMaxRoomsCount(5)->create();

        $this->assertEquals(5, count($map->getTilesByType(TileTypes::Room)));
    }

    /** @test */
    public function corridors_length_range_can_be_set()
    {
        $map = $this
            ->builder
            ->setMaxRoomsCount(2)
            ->setMinCorridorLength(2)
            ->setMaxCorridorLength(5)
            ->create();

        $this->assertGreaterThanOrEqual(2, count($map->getTilesByType(TileTypes::Corridor)));
        $this->assertLessThanOrEqual(5, count($map->getTilesByType(TileTypes::Corridor)));
        $this->assertGreaterThanOrEqual(4, count($map->getTilesByType(...TileTypes::cases())));
        $this->assertLessThanOrEqual(7, count($map->getTilesByType(...TileTypes::cases())));
    }
}
