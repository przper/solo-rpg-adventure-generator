<?php

namespace App\Game;

use App\EncountersPlanning\EncountersPlanningStrategy;
use App\EncountersPlanning\TeamChallengeRating;
use App\Game\Encounters\MapBasedEncounters;
use App\Game\FogOfWar\PersistentFogOfWar;
use App\MapBuilding\MapBuildingStrategy;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

class GameFactory
{
    /** @var MapBuildingStrategy[] */
    private array $mapBuildingStrategies = [];

    /** @var EncountersPlanningStrategy[] $encountersPlanningStrategies */
    private array $encountersPlanningStrategies = [];

    /**
     * @param iterable<MapBuildingStrategy> $mapBuildingStrategies
     * @param iterable<EncountersPlanningStrategy> $encountersPlanningStrategies
     */
    public function __construct(
        #[TaggedIterator('map_building.strategy')]
        iterable $mapBuildingStrategies,
        #[TaggedIterator('encounters_planning.strategy')]
        iterable $encountersPlanningStrategies,
    ) {
        foreach ($mapBuildingStrategies as $strategy) {
            $this->mapBuildingStrategies[$strategy->supports()->name] = $strategy;
        }

        foreach ($encountersPlanningStrategies as $strategy) {
            $this->encountersPlanningStrategies[$strategy->supports()->name] = $strategy;
        }
    }

    public function create(NewGameDTO $newGame): Game
    {
        $map = $this
            ->mapBuildingStrategies[$newGame->mapType->name]
            ->create($newGame->length);

        $encountersPlan = $this
            ->encountersPlanningStrategies[$newGame->system->name]
            ->plan($newGame->length, TeamChallengeRating::fromLevelsAsIntegers(...$newGame->playerLevels));

        $fogOfWar = new PersistentFogOfWar($map);
        $encounters = new MapBasedEncounters($map, $encountersPlan);

        return new Game($map, $fogOfWar, $encounters);
    }
}
