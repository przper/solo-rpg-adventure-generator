<?php

namespace App\Tests\Unit\EncounterPlanner;

use App\Service\EncounterPlanner\TeamChallangeRating;
use PHPUnit\Framework\TestCase;

class TeamChallangeRatingTest extends TestCase
{
    /**
     * @test
     * @dataProvider parties
     */
    public function it_calculates_party_experience_treshold(string $difficulty, array $levels, int $expectedTreshold)
    {
        $teamChallangeRating = TeamChallangeRating::fromLevelsAsIntegers(...$levels);

        $this->assertEquals($expectedTreshold, $teamChallangeRating->getExperienceTresholdForDifficulty($difficulty));
    }

    public function parties()
    {
        return [
            [TeamChallangeRating::DIFFICULTY_EASY, [3, 3, 3, 2], 275],
            [TeamChallangeRating::DIFFICULTY_MEDIUM, [3, 3, 3, 2], 550],
            [TeamChallangeRating::DIFFICULTY_HARD, [3, 3, 3, 2], 825],
            [TeamChallangeRating::DIFFICULTY_DEADLY, [3, 3, 3, 2], 1400],
        ];
    }
}