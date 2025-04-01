<?php

namespace App\Tests\Integration\EncountersPlanner\DungeonsAndDragons5Edition;

use App\EncountersPlanning\DungeonsAndDragons5Edition\AdjustedExperienceFromEncounterCalculator;
use App\EncountersPlanning\DungeonsAndDragons5Edition\EncounterGenerator;
use App\EncountersPlanning\EncounterDifficulty;
use App\EncountersPlanning\TeamChallengeRating;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EncounterGeneratorTest extends KernelTestCase
{
    private EncounterGenerator $generator;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->generator = self::getContainer()->get(EncounterGenerator::class);
    }

    /** @test */
    public function it_creates_encounter_within_experience_treshold_of_given_difficulty(): void
    {
        $team = TeamChallengeRating::fromLevelsAsIntegers(2, 2, 2, 2);
        $encounter = $this->generator->create(EncounterDifficulty::MEDIUM, $team);

        $this->assertIsArray($encounter->getEnemies());
        $this->assertGreaterThanOrEqual(1, count($encounter->getEnemies()));
        $this->assertGreaterThanOrEqual(200, (new AdjustedExperienceFromEncounterCalculator())->getExperienceSum($encounter));
        $this->assertLessThanOrEqual(600, (new AdjustedExperienceFromEncounterCalculator())->getExperienceSum($encounter));
    }
}
