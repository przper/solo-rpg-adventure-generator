<?php

namespace App\Game;

use App\EncountersPlanning\EncountersPlannerInterface;
use App\EncountersPlanning\TeamChallengeRating;
use App\Game\Encounters\MapBasedEncounters;
use App\Game\FogOfWar\PersistentFogOfWar;
use App\MapBuilding\MapGeneratorStrategyInterface;

class GameFactory
{
    public function __construct(
        private MapGeneratorStrategyInterface $mapGeneratorStrategy,
        private EncountersPlannerInterface $encountersPlanner,
    ) {
    }

    public function create(NewGameDTO $newGame): Game
    {
        $mapGenerator = $this->mapGeneratorStrategy->get($newGame->mapType, $newGame->length);
        $map = $mapGenerator->create();

        $encounterPlan = $this->encountersPlanner->plan(
            $newGame->length,
            TeamChallengeRating::fromLevelsAsIntegers(...$newGame->playerLevels),
        );

        $fogOfWar = new PersistentFogOfWar($map);
        $encounters = new MapBasedEncounters($map, $encounterPlan);

        return new Game($map, $fogOfWar, $encounters);
    }
}
