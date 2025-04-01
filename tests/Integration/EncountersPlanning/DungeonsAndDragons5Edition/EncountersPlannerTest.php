<?php

namespace App\Tests\Integration\EncountersPlanner\DungeonsAndDragons5Edition;

use App\Core\Enum\DungeonLength;
use App\EncountersPlanning\DungeonsAndDragons5Edition\EncountersPlanner;
use App\EncountersPlanning\EncounterDifficulty;
use App\EncountersPlanning\EncountersPlan;
use App\EncountersPlanning\TeamChallengeRating;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EncountersPlannerTest extends KernelTestCase
{
    /** @test */
    public function it_creates_EncounterPlan()
    {
        /** @var EncountersPlanner $planner */
        $planner = static::getContainer()->get(EncountersPlanner::class);

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
