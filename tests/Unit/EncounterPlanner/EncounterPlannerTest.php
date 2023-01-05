<?php

namespace App\Tests\Unit\RailroadMapGenerator;

use PHPUnit\Framework\TestCase;
use App\Service\EncounterPlanner\EncounterPlanner;

class EncounterPlannerTest extends TestCase
{
    /** @test */
    public function test()
    {
        $planner = new EncounterPlanner();

        $test = $planner->plan(EncounterPlanner::DUNGEON_SIZE_SHORT, 2);

        // dump($test);
    }
}