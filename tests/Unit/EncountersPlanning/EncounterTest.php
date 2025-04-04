<?php

namespace App\Tests\Unit\EncountersPlanning;

use App\Core\Encounter\Encounter;
use App\Core\Encounter\EncounterDifficulty;
use App\Core\Encounter\Enemy;
use App\Core\Encounter\Obstacle;
use App\Core\Encounter\Treasure;
use App\Core\Helper\DiceStack;
use PHPUnit\Framework\TestCase;

class EncounterTest extends TestCase
{
    public function testResolveAllSlain(): void
    {
        $enemy1 = $this->createEnemy();
        $enemy2 = $this->createEnemy();

        $encounter = new Encounter(EncounterDifficulty::MEDIUM, [$enemy1, $enemy2]);

        $this->assertFalse($encounter->isResolved());

        $encounter->resolve('all_slain');

        $this->assertFalse($enemy1->isAlive());
        $this->assertFalse($enemy2->isAlive());
        $this->assertTrue($encounter->isResolved());
    }

    public function testResolveRemoveObstacle(): void
    {
        $obstacle = new Obstacle('Spike Trap', 12);

        $encounter = new Encounter(EncounterDifficulty::MEDIUM, obstacles: [$obstacle]);

        $this->assertFalse($encounter->isResolved());

        $encounter->resolve('obstacle_removed');

        $this->assertTrue($encounter->isResolved());
        $this->assertTrue($obstacle->isRemoved());
    }

    public function testResolvePickUpTreasure(): void
    {
        $gold = new Treasure('Gold Coins');
        $gems = new Treasure('Gems');

        $encounter = new Encounter(EncounterDifficulty::EASY, treasures: [$gold, $gems]);

        $this->assertFalse($gold->isPickedUp());
        $this->assertFalse($gems->isPickedUp());
        $this->assertFalse($encounter->isResolved());

        $encounter->resolve('treasure_picked_up:0');

        $this->assertTrue($gold->isPickedUp());
        $this->assertFalse($gems->isPickedUp());
        $this->assertFalse($encounter->isResolved());
    }

    private function createEnemy(): Enemy
    {
        return new Enemy(1.0, 100, 'Baziak', DiceStack::fromString('1d12'), 10, DiceStack::fromString('1d6'));
    }
}
