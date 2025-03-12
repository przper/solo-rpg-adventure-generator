<?php

namespace App\Tests\Unit\MapRenderer;

use App\Helper\Coordinates;
use App\Service\Game\Game;
use App\Service\Game\PlayerPosition;
use App\Service\Map\Core\TileType;
use App\Tests\Unit\MapRenderer\Fixtures\DummyCorridor;
use App\Tests\Unit\MapRenderer\Fixtures\DummyRoom;
use PHPUnit\Framework\TestCase;
use App\Service\MapRenderer\CellWrapper;

class CellWrapperTest extends TestCase
{
    /** @test */
    public function it_gets_attributes_from_baseCell()
    {
        $wrapper = CellWrapper::fromTile(new DummyRoom());

        $this->assertEquals(TileType::Room, $wrapper->type);

        $this->assertFalse($wrapper->getHasPlayer());
        $this->assertFalse($wrapper->getWasVisited());

        $this->assertEquals(
            Coordinates::fromIntegers(0, 0),
            $wrapper->coordinates
        );

        $this->assertNull($wrapper->treasure);
    }

    /** @test */
    public function it_resolves_templates()
    {
        $roomWrapper = CellWrapper::fromTile(new DummyRoom());
        $roomWrapper->setWasVisited(true);

        $this->assertTrue($roomWrapper->getWasVisited());
        $this->assertEquals(CellWrapper::ROOM_TEMPLATE, $roomWrapper->getTemplate());

        $corridorWrapper = CellWrapper::fromTile(new DummyCorridor());
        $corridorWrapper->setWasVisited(true);

        $this->assertTrue($corridorWrapper->getWasVisited());
        $this->assertEquals(CellWrapper::CORRIDOR_TEMPLATE, $corridorWrapper->getTemplate());
    }

    /** @test */
    public function not_visited_cells_are_hidden()
    {
        $roomWrapper = CellWrapper::fromTile(new DummyRoom());

        $this->assertFalse($roomWrapper->getWasVisited());
        $this->assertEquals(CellWrapper::WALL_TEMPLATE, $roomWrapper->getTemplate());

        $corridorWrapper = CellWrapper::fromTile(new DummyCorridor());

        $this->assertFalse($corridorWrapper->getWasVisited());
        $this->assertEquals(CellWrapper::WALL_TEMPLATE, $corridorWrapper->getTemplate());
    }

    /**
     * @test
     */
    public function game_state_can_be_applied_to_CellWrapper()
    {
        $room1 = new DummyRoom(0, 0);
        $corridor = new DummyCorridor(1, 0);
        $room2 = new DummyRoom(2, 0);

        $game = $this->createMock(Game::class);
        $game->method('getPlayerPosition')->willReturn(new PlayerPosition(Coordinates::fromIntegers(1, 0)));
        $game->method('getVisitedCells')->willReturn([$room1->getCoordinates(), $corridor->getCoordinates()]);

        $wrappedRoom1 = CellWrapper::fromTile($room1);
        $wrappedRoom1->applyGameState($game);
        $wrappedCorridor = CellWrapper::fromTile($corridor);
        $wrappedCorridor->applyGameState($game);
        $wrappedRoom2 = CellWrapper::fromTile($room2);
        $wrappedRoom2->applyGameState($game);

        $this->assertTrue($wrappedRoom1->getWasVisited());
        $this->assertFalse($wrappedRoom1->getHasPlayer());

        $this->assertTrue($wrappedCorridor->getWasVisited());
        $this->assertTrue($wrappedCorridor->getHasPlayer());

        $this->assertFalse($wrappedRoom2->getWasVisited());
        $this->assertFalse($wrappedRoom2->getHasPlayer());
    }
}
