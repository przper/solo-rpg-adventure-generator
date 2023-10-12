<?php

namespace App\Tests\Unit\Game;

use App\Service\EncountersPlanner\EncountersPlanner;
use App\Service\Game\Game;
use PHPUnit\Framework\TestCase;
use App\Service\Game\GameFactory;
use App\Interface\MapGeneratorInterface;
use App\Tests\Unit\Game\Fixtures\DummyMapGenerator;

class GameFactoryTest extends TestCase
{
    private EncountersPlanner $encountersPlanner;

    protected function setUp(): void
    {
        $this->encountersPlanner = $this->createMock(EncountersPlanner::class);
    }

    /** @test */
    public function it_has_map_builder()
    {
        $factory = new GameFactory($this->encountersPlanner);

        $factory->setMapBuilder(new DummyMapGenerator());

        $this->assertInstanceOf(MapGeneratorInterface::class, $factory->getMapBuilder());
    }

    /** @test */
    public function it_creates_games()
    {
        $this->encountersPlanner
            ->expects($this->once())
            ->method('plan');

        $factory = new GameFactory($this->encountersPlanner);
        $factory->setMapBuilder(new DummyMapGenerator());

        $game = $factory->create();

        $this->assertInstanceOf(Game::class, $game);
        $this->assertNotNull($game->getPlayerPosition());
        $this->assertNotNull($game->getMap());
    }
}
