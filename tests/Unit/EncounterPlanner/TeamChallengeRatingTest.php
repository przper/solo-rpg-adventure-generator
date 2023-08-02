<?php

namespace App\Tests\Unit\EncounterPlanner;

use App\Service\EncountersPlanner\TeamChallengeRating;
use PHPUnit\Framework\TestCase;

class TeamChallengeRatingTest extends TestCase
{
    /**
     * @test
     * @dataProvider parties
     */
    public function it_calculates_party_experience_treshold(string $difficulty, array $levels, int $expectedTreshold)
    {
        $teamChallengeRating = TeamChallengeRating::fromLevelsAsIntegers(...$levels);

        $this->assertEquals($expectedTreshold, $teamChallengeRating->getExperienceTresholdForDifficulty($difficulty));
    }

    public function parties()
    {
        return [
            [TeamChallengeRating::DIFFICULTY_EASY, [3, 3, 3, 2], 275],
            [TeamChallengeRating::DIFFICULTY_MEDIUM, [3, 3, 3, 2], 550],
            [TeamChallengeRating::DIFFICULTY_HARD, [3, 3, 3, 2], 825],
            [TeamChallengeRating::DIFFICULTY_DEADLY, [3, 3, 3, 2], 1400],
        ];
    }
}
