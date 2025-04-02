<?php

namespace App\Tests\Unit\Core;

use App\EncountersPlanning\TeamChallengeRating;
use PHPUnit\Framework\TestCase;

class TeamChallengeRatingTest extends TestCase
{
    /**
     * @dataProvider provideTeamLevels
     */
    public function testGetAveragePlayerLevel(array $teamLevels, int $expectedAverage): void
    {
        $teamChallengeRating = new TeamChallengeRating($teamLevels);
        $this->assertSame($expectedAverage, $teamChallengeRating->getAveragePlayerLevel());
    }

    /**
     * Provides test cases for team levels and the expected average player level.
     */
    public function provideTeamLevels(): array
    {
        return [
            'Empty team levels' => [[], 0],
            'Single player level' => [[5], 5],
            'Multiple player levels with exact average' => [[5, 7, 9], 7],
            'Multiple player levels with fractional average' => [[5, 6, 7], 6],
            'Multiple identical levels' => [[10, 10, 10], 10]
        ];
    }
}
