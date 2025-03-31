<?php

namespace App\Tests\Service\EncountersPlanner;

use App\Enum\EncounterDifficulty;
use App\Helper\DiceStack;
use App\Service\EncountersPlanner\Encounter;
use App\Service\EncountersPlanner\Enemy;
use PHPUnit\Framework\TestCase;

class EncounterTest extends TestCase
{
    public function testResolveAllSlain(): void
    {
        $enemy1 = $this->createEnemy();
        $enemy2 = $this->createEnemy();

        $encounter = new Encounter(EncounterDifficulty::MEDIUM, [$enemy1, $enemy2]);

        $encounter->resolve('all_slain');

        $this->assertFalse($enemy1->isAlive());
        $this->assertFalse($enemy2->isAlive());
    }

    public function testResolveNoAction(): void
    {
        $enemy1 = $this->createEnemy();
        $enemy2 = $this->createEnemy();

        $encounter = new Encounter(EncounterDifficulty::MEDIUM, [$enemy1, $enemy2]);

        $encounter->resolve('some_other_result');

        $this->assertTrue($enemy1->isAlive());
        $this->assertTrue($enemy2->isAlive());
    }

    private function createEnemy(): Enemy
    {
        return new Enemy(1.0, 100, 'Baziak', DiceStack::fromString('1d12'), 10, DiceStack::fromString('1d6'));
    }
}
