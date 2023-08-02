<?php

namespace App\Tests\Integration\EncounterPlanner;

use App\Service\EncounterPlanner\Encounter;
use App\Service\EncounterPlanner\EncounterGenerator;
use App\Service\EncounterPlanner\TeamChallengeRating;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EncounterGeneratorTest extends KernelTestCase
{
    /** @test */
    public function it_works()
    {
        /** @var EncounterGenerator $encounterGenerator */
        $encounterGenerator = static::getContainer()->get(EncounterGenerator::class);

        $difficulty = Encounter::DIFFICULTY_MEDIUM;
        $team = TeamChallengeRating::fromLevelsAsIntegers(1, 1);
        $encounter = $encounterGenerator->create($difficulty, $team);

        dump(implode("|", array_map(fn($e) => $e->getName(), $encounter->getEnemies())));
        dump($team->getExperienceTresholdForDifficulty($difficulty));
        dump($team->getExperienceTresholdForDifficulty(Encounter::DIFFICULTY_HARD));

        $this->assertIsArray($encounter->getEnemies());
    }
}
