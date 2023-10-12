<?php

namespace App\Tests\Unit\EncounterPlanner;

use App\Enum\EncounterDifficulty;
use App\Service\EncountersPlanner\TeamChallengeRating;
use PHPUnit\Framework\TestCase;

class TeamChallengeRatingTest extends TestCase
{
    /**
     * @test
     * @dataProvider parties
     */
    public function it_calculates_party_experience_treshold(EncounterDifficulty $difficulty, array $levels, int $expectedTreshold)
    {
        $teamChallengeRating = TeamChallengeRating::fromLevelsAsIntegers(...$levels);

        $this->assertEquals($expectedTreshold, $teamChallengeRating->getExperienceTresholdForDifficulty($difficulty));
    }

    public function parties()
    {
        return [
            [EncounterDifficulty::EASY, [3, 3, 3, 2], 275],
            [EncounterDifficulty::MEDIUM, [3, 3, 3, 2], 550],
            [EncounterDifficulty::HARD, [3, 3, 3, 2], 825],
            [EncounterDifficulty::DEADLY, [3, 3, 3, 2], 1400],
        ];
    }
}
