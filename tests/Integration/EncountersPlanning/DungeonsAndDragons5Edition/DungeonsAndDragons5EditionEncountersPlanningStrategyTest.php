<?php

namespace App\Tests\Integration\EncountersPlanner\DungeonsAndDragons5Edition;

use App\Core\Encounter\EncounterDifficulty;
use App\Core\Map\DungeonLength;
use App\EncountersPlanning\DungeonsAndDragons5Edition\DungeonsAndDragons5EncountersPlanningStrategy;
use App\EncountersPlanning\EncountersPlan;
use App\EncountersPlanning\TeamChallengeRating;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DungeonsAndDragons5EditionEncountersPlanningStrategyTest extends KernelTestCase
{
    /** @test */
    public function it_creates_EncounterPlan_with_correct_number_of_encounters(): void
    {
        $planner = static::getContainer()->get(DungeonsAndDragons5EncountersPlanningStrategy::class);

        for ($i = 0; $i < 50; $i++) {
            $encountersPlan = $planner->plan(DungeonLength::MEDIUM, TeamChallengeRating::fromLevelsAsIntegers(2, 2, 2, 2));

            $this->assertInstanceOf(EncountersPlan::class, $encountersPlan);
            $this->assertLessThanOrEqual(12, count($encountersPlan->encounters));
            $this->assertGreaterThanOrEqual(2, count($encountersPlan->getEncountersByDifficulty(EncounterDifficulty::EASY)));
            $this->assertGreaterThanOrEqual(5, count($encountersPlan->getEncountersByDifficulty(EncounterDifficulty::MEDIUM)));
            $this->assertGreaterThanOrEqual(1, count($encountersPlan->getEncountersByDifficulty(EncounterDifficulty::HARD)));
            $this->assertLessThanOrEqual(1, count($encountersPlan->getEncountersByDifficulty(EncounterDifficulty::DEADLY)));
        }
    }
}
