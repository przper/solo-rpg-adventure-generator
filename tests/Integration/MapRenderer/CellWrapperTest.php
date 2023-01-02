<?php

namespace App\Tests\Integration\MapRenderer;

use App\Service\Game\Game;
use App\Service\Game\PlayerPosition;
use App\Service\MapRenderer\CellWrapper;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Tests\Integration\MapRenderer\Fixtures\DummyMap;
use App\Tests\Integration\MapRenderer\Fixtures\DummyMapCell;

class CellWrapperTest extends KernelTestCase
{
    /** @test */
    public function it_can_apply_game_state_to_itself()
    {
        static::bootKernel();

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
