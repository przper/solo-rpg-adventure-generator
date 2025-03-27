<?php

namespace App\Tests\Unit\Game;

use App\Service\Game\Game;
use App\Service\Game\GameFactory;
use App\Service\Game\NewGameDTO;
use App\Service\Game\PlayerPosition;
use App\Service\Map\Core\Map;
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
