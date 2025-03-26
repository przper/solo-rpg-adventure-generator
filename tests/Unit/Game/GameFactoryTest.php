<?php

namespace App\Tests\Unit\Game;

use App\Interface\MapGeneratorInterface;
use App\Service\EncountersPlanner\EncountersPlan;
use App\Service\EncountersPlanner\EncountersPlannerInterface;
use App\Service\Game\Game;
use App\Service\Game\GameFactory;
use App\Tests\Unit\Game\Fixtures\DummyMapGenerator;
use PHPUnit\Framework\TestCase;

class GameFactoryTest extends TestCase
{
    private EncountersPlannerInterface $encountersPlanner;

    protected function setUp(): void
    {
        $this->encountersPlanner = $this->createMock(EncountersPlannerInterface::class);
        $this->encountersPlanner->method('plan')->willReturn(new EncountersPlan());
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
            ->method('plan')
            ->willReturn(new EncountersPlan());

        $factory = new GameFactory($this->encountersPlanner);
        $factory->setMapBuilder(new DummyMapGenerator());

        $game = $factory->create();

        $this->assertInstanceOf(Game::class, $game);
        $this->assertNotNull($game->getPlayerPosition());
        $this->assertNotNull($game->getMap());
    }
}
