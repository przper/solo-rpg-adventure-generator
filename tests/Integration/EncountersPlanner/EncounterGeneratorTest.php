<?php

namespace App\Tests\Integration\EncountersPlanner;

use App\Service\EncountersPlanner\Encounter;
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
        $difficulty = Encounter::DIFFICULTY_MEDIUM;
        $team = TeamChallengeRating::fromLevelsAsIntegers(1, 1);
        $encounter = $this->generator->create($difficulty, $team);

        dump(implode("|", array_map(fn($e) => $e->getName(), $encounter->getEnemies())));
        dump($team->getExperienceTresholdForDifficulty($difficulty));
        dump($team->getExperienceTresholdForDifficulty(Encounter::DIFFICULTY_HARD));

        $this->assertIsArray($encounter->getEnemies());
    }
}
