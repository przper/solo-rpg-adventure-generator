<?php

namespace App\Tests\Integration\GridMapGenerator;

use App\Helper\Coordinates;
use App\Service\Map\Core\Map;
use App\Service\Map\Core\TileType;
use App\Service\Map\Grid\GridMapBuilder;
use App\Tests\DebugsMap;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GridMapBuilderTest extends KernelTestCase
{
    use DebugsMap;

    private GridMapBuilder $builder;

    protected function setUp(): void
    {
        $this->builder = static::getContainer()->get(GridMapBuilder::class);
    }

    /** @test */
    public function it_builds_map_with_correct_dimensions(): void
    {
        $map = $this
            ->builder
            ->setGridWidth(3)
            ->setGridHeight(2)
            ->setRoomSize(1)
            ->setCorridorLength(2)
            ->create();

        /**
         * Expected result
         * R - Room, C - Corridor, # - empty space
         *
         * R C C R C C R
         * C # # C # # C
         * C # # C # # C
         * R # # R # # C
         */
        dump($this->debugMap($map));

        $this->assertInstanceOf(Map::class, $map);
        $this->assertCount(4, $map->tiles); // height
        $this->assertCount(7, $map->tiles[0]); // width
        $this->assertCount(7, $map->tiles[1]);
        $this->assertCount(7, $map->tiles[2]);
        $this->assertCount(7, $map->tiles[3]);
    }

    /** @test */
    public function it_puts_Rooms_in_grid_corners(): void
    {
        $map = $this
            ->builder
            ->setGridHeight(2)
            ->setGridWidth(2)
            ->setRoomSize(3)
            ->setCorridorLength(1)
            ->create();

        /**
         * Expected result
         * R - Room, C - Corridor, # - empty space
         *
         * R R R # R R R
         * R R R C R R R
         * R R R # R R R
         * # C # # # C #
         * R R R # R R R
         * R R R C R R R
         * R R R # R R R
         */
        dump($this->debugMap($map));

        $this->assertInstanceOf(Map::class, $map);

        // top left
        foreach (range(0, 2) as $x) {
            foreach (range(0, 2) as $y) {
                $this->assertEquals(TileType::Room, $map->getTile(Coordinates::fromIntegers($x, $y))->getType());
            }
        }

        // top right
        foreach (range(4, 6) as $x) {
            foreach (range(0, 2) as $y) {
                $this->assertEquals(TileType::Room, $map->getTile(Coordinates::fromIntegers($x, $y))->getType());
            }
        }

        // bottom left
        foreach (range(0, 2) as $x) {
            foreach (range(4, 6) as $y) {
                $this->assertEquals(TileType::Room, $map->getTile(Coordinates::fromIntegers($x, $y))->getType());
            }
        }

        // bottom right
        foreach (range(4, 6) as $x) {
            foreach (range(4, 6) as $y) {
                $this->assertEquals(TileType::Room, $map->getTile(Coordinates::fromIntegers($x, $y))->getType());
            }
        }
    }
}
