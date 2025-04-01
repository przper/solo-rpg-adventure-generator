<?php

namespace App\Tests\Unit\Game;

use App\Core\Map\Map;
use App\Game\Game;
use App\Game\GameFactory;
use App\Game\NewGameDTO;
use App\Game\PlayerPosition;
use App\Tests\Fixtures\Dummies\DummyMapGeneratorStrategy;
use App\Tests\Fixtures\Dummies\EmptyEncounterPlanner;
use PHPUnit\Framework\TestCase;

class GameFactoryTest extends TestCase
{
    /** @test */
    public function it_creates_games()
    {
        $factory = new GameFactory(new DummyMapGeneratorStrategy(), new EmptyEncounterPlanner());

        $game = $factory->create(new NewGameDTO());

        $this->assertInstanceOf(Game::class, $game);
        $this->assertInstanceOf(PlayerPosition::class, $game->getPlayerPosition());
        $this->assertInstanceOf(Map::class, $game->getMap());
    }
}
