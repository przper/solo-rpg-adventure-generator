<?php

namespace App\Tests\Unit\MapRenderer;

use App\Helper\Coordinates;
use App\Service\Game\Game;
use App\Service\Game\PlayerPosition;
use App\Service\Map\Core\Tile;
use App\Service\Map\Core\TileType;
use PHPUnit\Framework\TestCase;
use App\Service\MapRenderer\CellWrapper;

class CellWrapperTest extends TestCase
{
    /** @test */
    public function it_gets_attributes_from_baseCell()
    {
        $wrapper = CellWrapper::fromTile(new Tile(Coordinates::fromIntegers(0, 0), TileType::Room));

        $this->assertEquals(TileType::Room, $wrapper->type);

        $this->assertFalse($wrapper->hasPlayer());
        $this->assertFalse($wrapper->wasVisited());

        $this->assertEquals(
            Coordinates::fromIntegers(0, 0),
            $wrapper->coordinates,
        );
    }

    /** @test */
    public function it_resolves_templates()
    {
        $roomWrapper = CellWrapper::fromTile(new Tile(Coordinates::fromIntegers(0, 0), TileType::Room));
        $this->assertEquals(CellWrapper::WALL_TEMPLATE, $roomWrapper->getTemplate());

        $roomWrapper->setIsKnown(true);
        $this->assertEquals(CellWrapper::ROOM_TEMPLATE, $roomWrapper->getTemplate());

        $corridorWrapper = CellWrapper::fromTile(new Tile(Coordinates::fromIntegers(0, 0), TileType::Corridor));
        $this->assertEquals(CellWrapper::WALL_TEMPLATE, $corridorWrapper->getTemplate());

        $corridorWrapper->setIsKnown(true);

        $this->assertEquals(CellWrapper::CORRIDOR_TEMPLATE, $corridorWrapper->getTemplate());
    }

    /** @test */
    public function not_visited_cells_are_hidden()
    {
        $roomWrapper = CellWrapper::fromTile(new Tile(Coordinates::fromIntegers(0, 0), TileType::Room));

        $this->assertFalse($roomWrapper->wasVisited());
        $this->assertEquals(CellWrapper::WALL_TEMPLATE, $roomWrapper->getTemplate());

        $corridorWrapper = CellWrapper::fromTile(new Tile(Coordinates::fromIntegers(0, 0), TileType::Corridor));

        $this->assertFalse($corridorWrapper->wasVisited());
        $this->assertEquals(CellWrapper::WALL_TEMPLATE, $corridorWrapper->getTemplate());
    }

    /**
     * @test
     */
    public function game_state_can_be_applied_to_CellWrapper()
    {
        $room1 = new Tile(Coordinates::fromIntegers(0, 0), TileType::Room);
        $corridor1 = new Tile(Coordinates::fromIntegers(1, 0), TileType::Corridor);
        $room2 = new Tile(Coordinates::fromIntegers(2, 0), TileType::Room);
        $corridor2 = new Tile(Coordinates::fromIntegers(3, 0), TileType::Corridor);
        $room3 = new Tile(Coordinates::fromIntegers(3, 0), TileType::Room);

        $game = $this->createMock(Game::class);
        $game->method('getPlayerPosition')->willReturn(new PlayerPosition(Coordinates::fromIntegers(1, 0)));

        $game
            ->method('isVisited')
            ->willReturnCallback(function(Coordinates $coords) use ($room1, $corridor1) {
                return $coords->isSame($room1->coordinates) || $coords->isSame($corridor1->coordinates);
            });

        $game
            ->method('isKnown')
            ->willReturnCallback(function(Coordinates $coords) use ($room1, $corridor1, $room2) {
                if ($coords->isSame($room1->coordinates)) {
                    return true;
                }

                if ($coords->isSame($corridor1->coordinates)) {
                    return true;
                }

                if ($coords->isSame($room2->coordinates)) {
                    return true;
                }

                return false;
            });

        $wrappedRoom1 = CellWrapper::fromTile($room1);
        $wrappedRoom1->applyGameState($game);
        $wrappedCorridor1 = CellWrapper::fromTile($corridor1);
        $wrappedCorridor1->applyGameState($game);
        $wrappedRoom2 = CellWrapper::fromTile($room2);
        $wrappedRoom2->applyGameState($game);
        $wrappedCorridor2 = CellWrapper::fromTile($corridor2);
        $wrappedCorridor2->applyGameState($game);
        $wrappedRoom3 = CellWrapper::fromTile($room3);
        $wrappedRoom3->applyGameState($game);

        $this->assertTrue($wrappedRoom1->wasVisited());
        $this->assertTrue($wrappedRoom1->isKnown());
        $this->assertFalse($wrappedRoom1->hasPlayer());

        $this->assertTrue($wrappedCorridor1->isKnown());
        $this->assertTrue($wrappedCorridor1->wasVisited());
        $this->assertTrue($wrappedCorridor1->hasPlayer());

        $this->assertTrue($wrappedRoom2->isKnown());
        $this->assertFalse($wrappedRoom2->wasVisited());
        $this->assertFalse($wrappedRoom2->hasPlayer());

        $this->assertFalse($wrappedCorridor2->isKnown());
        $this->assertFalse($wrappedCorridor2->wasVisited());
        $this->assertFalse($wrappedCorridor2->hasPlayer());

        $this->assertFalse($wrappedRoom3->isKnown());
        $this->assertFalse($wrappedRoom3->wasVisited());
        $this->assertFalse($wrappedRoom3->hasPlayer());
    }
}
