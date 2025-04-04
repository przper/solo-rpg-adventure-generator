<?php

namespace App\EncountersPlanning\Shadowdark;

use App\Core\Map\DungeonLength;
use App\EncountersPlanning\EncountersPlan;
use App\EncountersPlanning\EncountersPlanningStrategy;
use App\EncountersPlanning\TeamChallengeRating;
use App\EncountersPlanning\TTRPGSystem;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

class ShadowdarkEncountersPlanningStrategy implements EncountersPlanningStrategy
{
    /** @var array<string, EncounterStrategy> $encounterStrategies */
    private array $encounterStrategies = [];

    /**
     * @param iterable<EncounterStrategy> $encounterStrategies
     */
    public function __construct(
        #[TaggedIterator('encounters_planning.shadowdark.encounter')]
        iterable $encounterStrategies,
    ) {
        foreach ($encounterStrategies as $encounterStrategy) {
            $this->encounterStrategies[$encounterStrategy->getDungeonRoomType()->name] = $encounterStrategy;
        }
    }

    public function supports(): TTRPGSystem
    {
        return TTRPGSystem::Shadowdark;
    }

    public function plan(DungeonLength $length, TeamChallengeRating $teamLevels): EncountersPlan
    {
        $maxEncounterCount = match ($length) {
            DungeonLength::SHORT => 5,
            DungeonLength::MEDIUM => 8,
            DungeonLength::LONG => 12,
        };

        $encounters = [];
        $roomCountPerType = array_fill_keys(
            array_map(fn(DungeonRoomType $r) => $r->name, DungeonRoomType::cases()),
            0,
        );

        while (count($encounters) < $maxEncounterCount) {
            $encounterType = DungeonRoomType::rollRoomType();

            if (!array_key_exists($encounterType->name, $this->encounterStrategies)) {
                continue; // just reroll if not recognized
            }

            if ($encounterType === DungeonRoomType::NPC) {
                continue; // not supported yet, just reroll
            }

            if ($encounterType === DungeonRoomType::Boss_Monster && $roomCountPerType[DungeonRoomType::Boss_Monster->name]) {
                continue; // max 1 per dungeon, just reroll
            }

            if (
                $encounterType === DungeonRoomType::Empty
            ) {
                continue; // intentional, reroll to have more "fun"
            }

            $strategy = $this->encounterStrategies[$encounterType->name];

            $encounters[] = $strategy->createEncounter();
            $roomCountPerType[$encounterType->name]++;
        }

        return new EncountersPlan($encounters);
    }
}
