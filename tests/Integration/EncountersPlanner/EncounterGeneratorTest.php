<?php

namespace App\Tests\Integration\EncountersPlanner;

use App\Enum\EncounterDifficulty;
use App\Service\EncountersPlanner\EncounterGenerator;
use App\Service\EncountersPlanner\TeamChallengeRating;
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
    public function it_works()
    {
        $difficulty = EncounterDifficulty::MEDIUM;
        $team = TeamChallengeRating::fromLevelsAsIntegers(2, 2, 2, 2);
        $encounter = $this->generator->create($difficulty, $team);

        dump(implode("|", array_map(fn($e) => $e->getName(), $encounter->getEnemies())));
        dump("Raw encounter experience: ".$encounter->getRawEnemiesExperienceSum());
        dump($team->getExperienceTresholdForDifficulty(EncounterDifficulty::EASY));
        dump($team->getExperienceTresholdForDifficulty(EncounterDifficulty::MEDIUM));
        dump($team->getExperienceTresholdForDifficulty(EncounterDifficulty::HARD));
        dump($team->getExperienceTresholdForDifficulty(EncounterDifficulty::DEADLY));

        $this->assertIsArray($encounter->getEnemies());
    }
}
