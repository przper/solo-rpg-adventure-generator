<?php

namespace App\Tests\Unit\EncountersPlanner;

use App\Enum\EncounterDifficulty;
use App\Service\EncountersPlanner\Encounter;
use App\Service\EncountersPlanner\EncountersPlan;
use PHPUnit\Framework\TestCase;

class EncountersPlanTest extends TestCase
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
    public function test_it_filters_encounters_by_difficulty(array $difficulties, array $expected): void
    {
        $filteredEncounters = $this->sut->getEncountersByDifficulty(...$difficulties);
        $this->assertEquals($expected, $filteredEncounters);
    }

    public function difficultyFilterDataProvider(): array
    {
        return [
            'Single difficulty filter' => [
                'difficultyFilter' => [EncounterDifficulty::EASY],
                'expected' => [
                    new Encounter(EncounterDifficulty::EASY),
                    new Encounter(EncounterDifficulty::EASY),
                ],
            ],
            'Multiple difficulty filter' => [
                'difficultyFilter' => [EncounterDifficulty::EASY, EncounterDifficulty::HARD],
                'expected' => [
                    new Encounter(EncounterDifficulty::EASY),
                    new Encounter(EncounterDifficulty::HARD),
                    new Encounter(EncounterDifficulty::EASY),
                ],
            ],
            'No match filter' => [
                'difficultyFilter' => [EncounterDifficulty::DEADLY],
                'expected' => [],
            ],
        ];
    }
}
