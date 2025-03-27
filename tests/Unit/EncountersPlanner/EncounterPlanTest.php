<?php

namespace App\Tests\Unit\EncountersPlanner;

use App\Enum\EncounterDifficulty;
use App\Service\EncountersPlanner\Encounter;
use App\Service\EncountersPlanner\EncountersPlan;
use PHPUnit\Framework\TestCase;

class EncounterPlanTest extends TestCase
{
    private EncountersPlan $sut;

    protected function setUp(): void
    {
        $this->sut = new EncountersPlan([
            new Encounter(EncounterDifficulty::EASY),
            new Encounter(EncounterDifficulty::MEDIUM),
            new Encounter(EncounterDifficulty::HARD),
            new Encounter(EncounterDifficulty::EASY),
        ]);
    }

    /**
     * @dataProvider difficultyFilterDataProvider
     */
    public function test_it_filters_encounters_by_difficulty(array $difficulties, int $expectedCount): void
    {
        $this->assertCount($expectedCount, $this->sut->getEncountersByDifficulty(...$difficulties));
    }

    public function difficultyFilterDataProvider(): array
    {
        return [
            'Single difficulty filter' => [
                'difficultyFilter' => [EncounterDifficulty::EASY],
                'expectedCount' => 2,
            ],
            'Multiple difficulty filter' => [
                'difficultyFilter' => [EncounterDifficulty::EASY, EncounterDifficulty::HARD],
                'expectedCount' => 3,
            ],
            'No match filter' => [
                'difficultyFilter' => [EncounterDifficulty::DEADLY],
                'expectedCount' => 0,
            ],
        ];
    }
}
