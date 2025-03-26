<?php

namespace App\Service\Game;

use App\Enum\DungeonLength;
use App\Interface\MapGeneratorInterface;
use App\Service\EncountersPlanner\EncountersPlannerInterface;
use App\Service\EncountersPlanner\TeamChallengeRating;

class GameFactory
{
    private MapGeneratorInterface $mapBuilder;

    public function __construct(
        private readonly EncountersPlannerInterface $encountersPlanner,
    ) {
    }

    public function getMapBuilder(): MapGeneratorInterface
    {
        return $this->mapBuilder;
    }

    public function setMapBuilder(MapGeneratorInterface $mapBuilder): self
    {
        $this->mapBuilder = $mapBuilder;

        return $this;
    }

    public function create(): Game
    {
        $map = $this->mapBuilder->create();
        $encounterPlan = $this->encountersPlanner->plan(DungeonLength::MEDIUM, TeamChallengeRating::fromLevelsAsIntegers(1, 1, 1, 1));

        return new Game($map, $encounterPlan);
    }
}
