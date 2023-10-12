<?php

namespace App\Tests\Integration\MapRenderer;

use App\Service\Game\Game;
use App\Service\Game\GameFactory;
use App\Service\MapRenderer\CellWrapper;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Tests\Integration\MapRenderer\Fixtures\DummyMapCell;
use App\Tests\Integration\MapRenderer\Fixtures\DummyMapGenerator;

class CellWrapperTest extends KernelTestCase
{
    private Game $game;

    public function setUp(): void
    {
        $this->game = static::getContainer()
            ->get(GameFactory::class)
            ->setMapBuilder(new DummyMapGenerator())
            ->create();
    }

    /** @test */
    public function it_can_apply_game_state_to_itself()
    {
        $wrapper = new CellWrapper(new DummyMapCell());

        $this->assertFalse($wrapper->getIsVisited());
        $this->assertFalse($wrapper->getHasPlayer());

        $this->game->start();
        $wrapper->applyGameState($this->game);

        $this->assertTrue($wrapper->getIsVisited());
        $this->assertTrue($wrapper->getHasPlayer());
    }
}
