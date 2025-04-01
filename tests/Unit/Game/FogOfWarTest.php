<?php

namespace App\Tests\Unit\Game;

use App\Core\Helper\Coordinates;
use App\Core\Map\Corridor;
use App\Core\Map\Map;
use App\Core\Map\Room;
use App\Game\FogOfWar\PersistentFogOfWar;
use PHPUnit\Framework\TestCase;

class FogOfWarTest extends TestCase
{
    private PersistentFogOfWar $sut;

    protected function setUp(): void
    {
        $map = new Map(3, 3, [
            Room::create([Coordinates::fromIntegers(0, 0)]),
            Corridor::create([Coordinates::fromIntegers(1, 0)]),
            Room::create([Coordinates::fromIntegers(2, 0)]),
            Corridor::create([Coordinates::fromIntegers(0, 1)]),
            Room::create([Coordinates::fromIntegers(0, 2)]),
        ]);
        /** Plain text example of map:
         * R - room, C - corridor, # - empty space
         *
         * R C R
         * C # #
         * R # #
         */

        $this->sut = new PersistentFogOfWar($map);
    }

    public function test_it_stores_revealed_tiles(): void
    {
        // initial state
        $this->assertCount(0, $this->sut->getVisitedCoordinates());

        // move Player to start position
        $this->sut->visit(Coordinates::fromIntegers(0, 0));
        $this->assertCount(1, $this->sut->getVisitedCoordinates());

        // move Player forward
        $this->sut->visit(Coordinates::fromIntegers(1, 0));
        $this->assertCount(2, $this->sut->getVisitedCoordinates());

        // move Player backward
        $this->sut->visit(Coordinates::fromIntegers(0, 0));
        $this->assertCount(2, $this->sut->getVisitedCoordinates());
    }

    public function test_it_stores_known_tiles(): void
    {
        // Initial state
        $this->assertCount(0, $this->sut->getKnownCoordinates());

        // move Player to start position
        $this->sut->visit(Coordinates::fromIntegers(0, 0));
        $this->assertCount(3, $this->sut->getKnownCoordinates()); // top left, top middle, middle left

        // Move Player forward
        $this->sut->visit(Coordinates::fromIntegers(1, 0));
        $this->assertCount(4, $this->sut->getKnownCoordinates()); // top left, top middle, middle left + top right

        // Move Player backward
        $this->sut->visit(Coordinates::fromIntegers(0, 0));
        $this->assertCount(4, $this->sut->getKnownCoordinates()); // no new tiles
    }

    public function test_check_if_tile_is_visited_by_coordinates(): void
    {
        $this->sut->visit(Coordinates::fromIntegers(0, 0));

        $this->assertTrue($this->sut->isVisited(Coordinates::fromIntegers(0, 0)));
        $this->assertFalse($this->sut->isVisited(Coordinates::fromIntegers(1, 0)));
    }

    public function test_check_if_tile_is_known_by_coordinates(): void
    {
        $this->sut->visit(Coordinates::fromIntegers(0, 0));
        $this->assertTrue($this->sut->isKnown(Coordinates::fromIntegers(0, 0)));
        $this->assertTrue($this->sut->isKnown(Coordinates::fromIntegers(1, 0)));
        $this->assertFalse($this->sut->isKnown(Coordinates::fromIntegers(2, 0)));
    }

    public function test_mark_whole_corridor_as_known_upon_entering(): void
    {
        // Create a map with the following layout:
        // R C C C R C R
        // # # # # # # #
        // Where R = Room, C = Corridor, # = empty
        $map = new Map(7, 2, [
            Room::create([Coordinates::fromIntegers(0, 0)]),
            Corridor::create([
                Coordinates::fromIntegers(1, 0),
                Coordinates::fromIntegers(2, 0),
                Coordinates::fromIntegers(3, 0),
            ]),
            Room::create([Coordinates::fromIntegers(4, 0)]),
            Corridor::create([Coordinates::fromIntegers(5, 0)]),
            Room::create([Coordinates::fromIntegers(6, 0)]),
        ]);

        $fogOfWar = new PersistentFogOfWar($map);

        $fogOfWar->visit(Coordinates::fromIntegers(0, 0)); // start game

        $knownTiles = array_map(fn(Coordinates $c) => (string) $c, $fogOfWar->getKnownCoordinates());
        $this->assertCount(2, $knownTiles);
        $this->assertContains('[0, 0]', $knownTiles); // starter
        $this->assertContains('[1, 0]', $knownTiles); // first tile of corridor

        $revealedTiles = array_map(fn(Coordinates $c) => (string) $c, $fogOfWar->getVisitedCoordinates());
        $this->assertCount(1, $revealedTiles);
        $this->assertContains('[0, 0]', $revealedTiles); // starter

        $fogOfWar->visit(Coordinates::fromIntegers(1, 0)); // enter corridor

        $knownTiles = array_map(fn(Coordinates $c) => (string) $c, $fogOfWar->getKnownCoordinates());
        $this->assertCount(5, $knownTiles);
        $this->assertContains('[0, 0]', $knownTiles); // starter
        $this->assertContains('[1, 0]', $knownTiles); // first tile of corridor
        $this->assertContains('[2, 0]', $knownTiles); // second tile of corridor
        $this->assertContains('[3, 0]', $knownTiles); // final corridor tile
        $this->assertContains('[4, 0]', $knownTiles); // next room

        $revealedTiles = array_map(fn(Coordinates $c) => (string) $c, $fogOfWar->getVisitedCoordinates());
        $this->assertCount(2, $revealedTiles);
        $this->assertContains('[0, 0]', $revealedTiles); // starter
        $this->assertContains('[1, 0]', $revealedTiles); // first tile of corridor
    }

    public function test_mark_whole_room_as_revealed_upon_entering(): void
    {
        $coordinates = [
            Coordinates::fromIntegers(0, 0),
            Coordinates::fromIntegers(1, 0),
            Coordinates::fromIntegers(2, 0),
            Coordinates::fromIntegers(0, 1),
            Coordinates::fromIntegers(1, 1),
            Coordinates::fromIntegers(2, 1),
            Coordinates::fromIntegers(0, 2),
            Coordinates::fromIntegers(1, 2),
            Coordinates::fromIntegers(2, 2),
        ];

        // Create a map with the following layout:
        // R R R #
        // R R R C
        // R R R #
        // # C #
        // Where R = Room, C = Corridor, # = empty
        $map = new Map(3, 6, [
            Room::create($coordinates),
            Corridor::create([
                Coordinates::fromIntegers(1, 3),
            ]),
            Corridor::create([
                Coordinates::fromIntegers(3, 1),
            ]),
        ]);

        $fogOfWar = new PersistentFogOfWar($map);

        $fogOfWar->visit(Coordinates::fromIntegers(0, 0)); // enter the first room

        $knownTiles = array_map(fn(Coordinates $c) => (string) $c, $fogOfWar->getKnownCoordinates());
        $this->assertCount(11, $knownTiles);
        foreach ($coordinates as $coordinate) {
            $this->assertContains((string)$coordinate, $knownTiles);
        }
        $this->assertContains('[1, 3]', $knownTiles); // right corridor
        $this->assertContains('[3, 1]', $knownTiles); // bottom corridor

        $revealedTiles = array_map(fn(Coordinates $c) => (string)$c, $fogOfWar->getVisitedCoordinates());
        foreach ($coordinates as $coordinate) {
            $this->assertContains((string)$coordinate, $revealedTiles);
        }
        $this->assertCount(9, $revealedTiles);
        $this->assertNotContains('[1, 3]', $revealedTiles); // right corridor
        $this->assertNotContains('[3, 1]', $revealedTiles); // bottom corridor
    }
}
