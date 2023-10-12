<?php

namespace App\Tests\Integration\EncountersPlanner;

use App\Enum\DungeonLength;
use App\Service\EncountersPlanner\EncountersPlan;
use App\Service\EncountersPlanner\EncountersPlanner;
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
        $this->assertGreaterThanOrEqual(11, $encountersPlan->count());
        $this->assertGreaterThanOrEqual(3, $encountersPlan->easyDifficultyCount());
        $this->assertGreaterThanOrEqual(5, $encountersPlan->mediumDifficultyCount());
        $this->assertGreaterThanOrEqual(1, $encountersPlan->hardDifficultyCount());
        $this->assertLessThanOrEqual(1, $encountersPlan->deadlyDifficultyCount());
    }
}
