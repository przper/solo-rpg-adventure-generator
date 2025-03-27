<?php

namespace App\Tests\Integration\EncountersPlanner\DungeonsAndDragons5Edition;

use App\Enum\DungeonLength;
use App\Enum\EncounterDifficulty;
use App\Service\EncountersPlanner\DungeonsAndDragons5Edition\EncountersPlanner;
use App\Service\EncountersPlanner\EncountersPlan;
use App\Service\EncountersPlanner\TeamChallengeRating;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EncountersPlannerTest extends KernelTestCase
{
    /** @test */
    public function it_creates_EncounterPlan()
    {
        /** @var EncountersPlanner $planner */
        $planner = static::getContainer()->get(EncountersPlanner::class);

        $encountersPlan = $planner->plan(DungeonLength::MEDIUM, TeamChallengeRating::fromLevelsAsIntegers(2, 2, 2, 2));

        $this->assertInstanceOf(EncountersPlan::class, $encountersPlan);
        $this->assertLessThan(12, count($encountersPlan->encounters));
        $this->assertGreaterThanOrEqual(2, count($encountersPlan->getEncountersByDifficulty(EncounterDifficulty::EASY)));
        $this->assertGreaterThanOrEqual(5, count($encountersPlan->getEncountersByDifficulty(EncounterDifficulty::MEDIUM)));
        $this->assertGreaterThanOrEqual(1, count($encountersPlan->getEncountersByDifficulty(EncounterDifficulty::HARD)));
        $this->assertLessThanOrEqual(1, count($encountersPlan->getEncountersByDifficulty(EncounterDifficulty::DEADLY)));
    }
}
