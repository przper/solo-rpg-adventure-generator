<?php

namespace App\Tests\Integration\EncountersPlanner;

use App\Service\EncounterPlanner\EncountersPlanner;
use App\Service\EncounterPlanner\TeamChallengeRating;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EncountersPlannerTest extends KernelTestCase
{
    /** @test */
    public function test()
    {
        /** @var EncountersPlanner $planner */
        $planner = static::getContainer()->get(EncountersPlanner::class);

        $encounters = $planner->plan(EncountersPlanner::DUNGEON_SIZE_SHORT, TeamChallengeRating::fromLevelsAsIntegers(2, 2));

         dump($encounters);
    }
}
