<?php

namespace App\Tests\Unit\Game;

use App\Helper\Coordinates;
use App\Service\Game\FogOfWar;
use App\Service\Map\Core\Map;
use App\Tests\Unit\Game\Fixtures\DummyCorridor;
use App\Tests\Unit\Game\Fixtures\DummyRoom;
use PHPUnit\Framework\TestCase;

class FogOfWarTest extends TestCase
{
    private FogOfWar $sut;

    protected function setUp(): void
    {
        $map = new Map(3, 3, [
            new DummyRoom(Coordinates::fromIntegers(0, 0)),
            new DummyCorridor(Coordinates::fromIntegers(1, 0)),
            new DummyRoom(Coordinates::fromIntegers(2, 0)),
            new DummyCorridor(Coordinates::fromIntegers(0, 1)),
            new DummyRoom(Coordinates::fromIntegers(0, 2)),
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
        $this->assertCount(0, $this->sut->getRevealedTiles());

        // move Player to start position
        $this->sut->visit(Coordinates::fromIntegers(0, 0));
        $this->assertCount(1, $this->sut->getRevealedTiles());

        // move Player forward
        $this->sut->visit(Coordinates::fromIntegers(1, 0));
        $this->assertCount(2, $this->sut->getRevealedTiles());

        // move Player backward
        $this->sut->visit(Coordinates::fromIntegers(0, 0));
        $this->assertCount(2, $this->sut->getRevealedTiles());
    }

    public function test_it_stores_known_tiles(): void
    {
        // Initial state
        $this->assertCount(0, $this->sut->getKnownTiles());

        // move Player to start position
        $this->sut->visit(Coordinates::fromIntegers(0, 0));
        $this->assertCount(3, $this->sut->getKnownTiles()); // top left, top middle, middle left

        // Move Player forward
        $this->sut->visit(Coordinates::fromIntegers(1, 0));
        $this->assertCount(4, $this->sut->getKnownTiles()); // top left, top middle, middle left + top right

        // Move Player backward
        $this->sut->visit(Coordinates::fromIntegers(0, 0));
        $this->assertCount(4, $this->sut->getKnownTiles()); // no new tiles
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
}
