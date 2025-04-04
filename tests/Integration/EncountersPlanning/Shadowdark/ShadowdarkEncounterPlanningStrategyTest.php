<?php

namespace App\Tests\Integration\EncountersPlanning\Shadowdark;

use App\Core\Map\DungeonLength;
use App\EncountersPlanning\Shadowdark\ShadowdarkEncountersPlanningStrategy;
use App\EncountersPlanning\TeamChallengeRating;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ShadowdarkEncounterPlanningStrategyTest extends KernelTestCase
{
    private ShadowdarkEncountersPlanningStrategy $sut;

    protected function setUp(): void
    {
        $this->sut = self::getContainer()->get(ShadowdarkEncountersPlanningStrategy::class);
    }

    public function test_it_differs_number_of_encounters_based_on_dungeon_length(): void
    {
        $planShort = $this->sut->plan(DungeonLength::SHORT, TeamChallengeRating::fromLevelsAsIntegers(1, 1));

        $this->assertCount(5, $planShort->encounters);

        $planMedium = $this->sut->plan(DungeonLength::MEDIUM, TeamChallengeRating::fromLevelsAsIntegers(1, 1));

        $this->assertCount(8, $planMedium->encounters);

        $planLong = $this->sut->plan(DungeonLength::LONG, TeamChallengeRating::fromLevelsAsIntegers(1, 1));

        $this->assertCount(12, $planLong->encounters);
    }
}
