<?php

namespace App\Tests\Unit\EncountersPlanning;

use App\Core\Helper\DiceStack;
use App\EncountersPlanning\Encounter;
use App\EncountersPlanning\EncounterDifficulty;
use App\EncountersPlanning\Enemy;
use App\EncountersPlanning\Obstacle;
use PHPUnit\Framework\TestCase;

class EncounterTest extends TestCase
{
    public function testResolveAllSlain(): void
    {
        $enemy1 = $this->createEnemy();
        $enemy2 = $this->createEnemy();

        $encounter = new Encounter(EncounterDifficulty::MEDIUM, [$enemy1, $enemy2]);

        $this->assertFalse($encounter->isResolved);

        $encounter->resolve('all_slain');

        $this->assertFalse($enemy1->isAlive());
        $this->assertFalse($enemy2->isAlive());
        $this->assertTrue($encounter->isResolved);
    }

    public function testResolveRemoveObstacle(): void
    {
        $obstacle = new Obstacle('Spike Trap');

        $encounter = new Encounter(EncounterDifficulty::MEDIUM, obstacles: [$obstacle]);

        $this->assertFalse($encounter->isResolved);

        $encounter->resolve('obstacle_removed');

        $this->assertTrue($encounter->isResolved);
        $this->assertTrue($obstacle->isRemoved());
    }

    private function createEnemy(): Enemy
    {
        return new Enemy(1.0, 100, 'Baziak', DiceStack::fromString('1d12'), 10, DiceStack::fromString('1d6'));
    }
}
