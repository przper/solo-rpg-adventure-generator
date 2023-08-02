<?php

namespace App\Tests\Integration\EncounterPlanner;

use App\Service\EncounterPlanner\EncounterPlanner;
use App\Service\EncounterPlanner\TeamChallengeRating;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EncounterPlannerTest extends KernelTestCase
{
    /** @test */
    public function test()
    {
        /** @var EncounterPlanner $planner */
        $planner = static::getContainer()->get(EncounterPlanner::class);

        $encounters = $planner->plan(EncounterPlanner::DUNGEON_SIZE_SHORT, TeamChallengeRating::fromLevelsAsIntegers(2, 2));

        // dump($encounters);
    }
}
