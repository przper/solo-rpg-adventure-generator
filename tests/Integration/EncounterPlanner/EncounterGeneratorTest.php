<?php

namespace App\Tests\Integration\EncounterPlanner;

use App\Service\EncounterPlanner\Encounter;
use App\Service\EncounterPlanner\EncounterGenerator;
use App\Service\EncounterPlanner\TeamChallangeRating;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EncounterGeneratorTest extends KernelTestCase
{
    /** @test */
    public function it_works()
    {
        /** @var EncounterGenerator $encounterGenerator */
        $encounterGenerator = static::getContainer()->get(EncounterGenerator::class);

        $team = TeamChallangeRating::fromLevelsAsIntegers(2, 2);
        $encounter = $encounterGenerator->create(Encounter::DIFFICULTY_MEDIUM, $team);

        // dump(count(array_map(fn($e) => $e->getName(), $encounter->getEnemies())));
        // dump($team->getExperienceTresholdForDifficulty(TeamChallangeRating::DIFFICULTY_MEDIUM));

        $this->assertIsArray($encounter->getEnemies());
    }
}