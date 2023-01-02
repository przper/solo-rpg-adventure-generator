<?php

namespace App\Tests\Unit\Game;

use App\Service\Game\Game;
use PHPUnit\Framework\TestCase;
use App\Service\Game\GameFactory;
use App\Interface\MapGeneratorInterface;
use App\Tests\Unit\Game\Fixtures\DummyMapGenerator;

class GameFactoryTest extends TestCase
{
    /** @test */
    public function it_has_map_builder()
    {
        $factory = new GameFactory();

        $factory->setMapBuilder(new DummyMapGenerator());

        $this->assertInstanceOf(MapGeneratorInterface::class, $factory->getMapBuilder());
    }

    /** @test */
    public function it_creates_games()
    {
        $factory = new GameFactory();
        $factory->setMapBuilder(new DummyMapGenerator());

        $game = $factory->create();

        $this->assertInstanceOf(Game::class, $game);
        $this->assertNotNull($game->getPlayerPosition());
        $this->assertNotNull($game->getMap());
    }
}
