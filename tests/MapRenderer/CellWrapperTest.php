<?php

namespace App\Tests\MapRenderer;

use App\Service\Game\Game;
use App\Service\Game\PlayerPosition;
use PHPUnit\Framework\TestCase;
use App\Service\MapRenderer\CellWrapper;
use App\Tests\MapRenderer\Fixtures\DummyMap;
use App\Tests\MapRenderer\Fixtures\DummyMapCell;

class CellWrapperTest extends TestCase
{
    /** @test */
    public function it_gets_attributes_from_baseCell()
    {
        $wrapper = new CellWrapper(new DummyMapCell());

        $this->assertEquals('DUMMY', $wrapper->type);

        $this->assertFalse($wrapper->getHasPlayer());
        $this->assertFalse($wrapper->getIsVisited());

        $this->assertEquals(0, $wrapper->x);
        $this->assertEquals(0, $wrapper->y);

        $this->assertNull($wrapper->treasure);
    }

    /** @test */
    public function it_can_apply_game_state_to_itself()
    {
        $cell = new DummyMapCell();
        $wrapper = new CellWrapper($cell);

        $game = new Game();
        $game->setMap(new DummyMap());
        $game->setPlayerPosition(PlayerPosition::fromCell($cell));

        $wrapper->applyGameState($game);

        $this->assertTrue($wrapper->getIsVisited());
        $this->assertTrue($wrapper->getHasPlayer());
    }
}
