<?php

namespace App\Tests\Unit\Game;

use App\Helper\Coordinates;
use App\Service\Game\FogOfWar;
use App\Service\Map\Core\Corridor;
use App\Service\Map\Core\Map;
use App\Service\Map\Core\Room;
use PHPUnit\Framework\TestCase;

class FogOfWarTest extends TestCase
{
    private FogOfWar $sut;

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

        $this->sut = new FogOfWar($map);
    }

    public function test_it_stores_revealed_tiles(): void
    {
        // initial state
        $this->assertCount(0, $this->sut->getRevealedCoordinates());

        // move Player to start position
        $this->sut->visit(Coordinates::fromIntegers(0, 0));
        $this->assertCount(1, $this->sut->getRevealedCoordinates());

        // move Player forward
        $this->sut->visit(Coordinates::fromIntegers(1, 0));
        $this->assertCount(2, $this->sut->getRevealedCoordinates());

        // move Player backward
        $this->sut->visit(Coordinates::fromIntegers(0, 0));
        $this->assertCount(2, $this->sut->getRevealedCoordinates());
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

        $fogOfWar = new FogOfWar($map);

        $fogOfWar->visit(Coordinates::fromIntegers(0, 0)); // start game

        $knownTiles = array_map(fn(Coordinates $c) => (string) $c, $fogOfWar->getKnownCoordinates());
        $this->assertCount(2, $knownTiles);
        $this->assertContains('[0, 0]', $knownTiles); // starter
        $this->assertContains('[1, 0]', $knownTiles); // first tile of corridor

        $revealedTiles = array_map(fn(Coordinates $c) => (string) $c, $fogOfWar->getRevealedCoordinates());
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

        $revealedTiles = array_map(fn(Coordinates $c) => (string) $c, $fogOfWar->getRevealedCoordinates());
        $this->assertCount(2, $revealedTiles);
        $this->assertContains('[0, 0]', $revealedTiles); // starter
        $this->assertContains('[1, 0]', $revealedTiles); // first tile of corridor
    }
}
