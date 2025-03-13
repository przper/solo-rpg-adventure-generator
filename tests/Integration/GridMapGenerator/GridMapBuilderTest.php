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
//        dump($this->debugMap($map));

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
//        dump($this->debugMap($map));

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

    /** @test */
    public function it_ensures_all_rooms_are_accessible_from_starting_room(): void
    {
        // Test a single map with specific parameters
        $map = $this
            ->builder
            ->setGridHeight(4)
            ->setGridWidth(5)
            ->setRoomSize(3)
            ->setCorridorLength(4)
            ->create();

        $this->assertRoomsAreAccessible($map);
    }

    /** @test */
    public function it_ensures_all_rooms_are_accessible_from_starter_room(): void
    {
        // Run the test 100 times with different map configurations
        for ($i = 0; $i < 100; $i++) {
            // Randomize builder config
            $gridWidth = rand(2, 4);
            $gridHeight = rand(2, 4);
            $roomSize = rand(1, 3);
            $corridorLength = rand(1, 4);

            $map = $this
                ->builder
                ->setGridHeight($gridHeight)
                ->setGridWidth($gridWidth)
                ->setRoomSize($roomSize)
                ->setCorridorLength($corridorLength)
                ->create();

            $this->assertRoomsAreAccessible(
                $map,
                "Iteration {$i}: {$gridWidth}x{$gridHeight} grid, room size {$roomSize}, corridor length {$corridorLength}"
            );
        }
    }

    /**
     * Checks that all rooms in a map are accessible from the starting room
     */
    private function assertRoomsAreAccessible(
        Map $map,
        string $message = ''
    ): void {

        // Get all room tiles
        $roomTiles = $map->getTilesByType(TileType::Room);
        $this->assertNotEmpty($roomTiles, $message ? $message . ': Map should have rooms' : 'Map should have rooms');

        // Get the starting room at coordinates (0,0)
        $startRoom = $map->getTile(Coordinates::fromIntegers(0, 0));
        $this->assertNotNull($startRoom, $message ? $message . ': Starting room should exist' : 'Starting room should exist');
        $this->assertEquals(TileType::Room, $startRoom->getType(), $message ? $message . ': Starting point should be a room' : 'Starting point should be a room');

        // Find all reachable tiles using breadth-first search
        $reachableTiles = $this->findReachableTiles($map, Coordinates::fromIntegers(0, 0));

        // Check if all rooms are reachable
        foreach ($roomTiles as $room) {
            $coordinates = $room->getCoordinates();
            $errorMessage = sprintf(
                'Room at (%d,%d) should be reachable from starting room%s',
                $coordinates->getX(),
                $coordinates->getY(),
                $message ? ' (' . $message . ')' : ''
            );

            $this->assertContains(
                $coordinates->getX() . ',' . $coordinates->getY(),
                $reachableTiles,
                $errorMessage
            );
        }
    }

    /**
     * Uses breadth-first search to find all tiles reachable from the given starting coordinates
     *
     * @param Map $map
     * @param Coordinates $start
     * @return array List of reachable coordinates in "x,y" format
     */
    private function findReachableTiles(Map $map, Coordinates $start): array
    {
        $queue = new \SplQueue();
        $queue->enqueue($start);

        $visited = [
            $start->getX() . ',' . $start->getY() => true
        ];

        while (!$queue->isEmpty()) {
            /** @var Coordinates $current */
            $current = $queue->dequeue();
            $currentTile = $map->getTile($current);

            // Skip if the tile doesn't exist or is a wall
            if ($currentTile === null || $currentTile->getType() === TileType::Wall) {
                continue;
            }

            // Get neighbors (adjacent tiles)
            $nearbyTiles = $map->getNearbyTiles($current);

            foreach ($nearbyTiles as $tile) {
                $coords = $tile->getCoordinates();
                $key = $coords->getX() . ',' . $coords->getY();

                // If we haven't visited this tile yet
                if (!isset($visited[$key])) {
                    $visited[$key] = true;
                    $queue->enqueue($coords);
                }
            }
        }

        return array_keys($visited);
    }
}
