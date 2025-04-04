<?php

namespace App\Tests\Integration\EncountersPlanner\DungeonsAndDragons5Edition;

use App\Core\Encounter\Enemy;
use App\Core\Helper\DiceStack;
use App\EncountersPlanning\DungeonsAndDragons5Edition\EnemyGenerator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EnemyGeneratorTest extends KernelTestCase
{
    private EnemyGenerator $generator;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->generator = self::getContainer()->get(EnemyGenerator::class);
    }

    public function test_it_generates_single()
    {
        $enemy = $this->generator->generate();

        $this->assertInstanceOf(Enemy::class, $enemy);
        $this->assertIsFloat($enemy->getChallengeRating(), 'Challenge rating should be a float');
        $this->assertIsInt($enemy->getExperiencePoints(), 'Experience points should be an integer');
        $this->assertGreaterThan(0, $enemy->getExperiencePoints(), 'Experience points should be positive');
        $this->assertNotEmpty($enemy->getName(), 'Enemy name should not be empty');
        $this->assertInstanceOf(DiceStack::class, $enemy->getHitDice(), 'Hit dice should be a DiceStack instance');
        $this->assertIsInt($enemy->getTotalHitPoints(), 'Hit points should be an integer');
        $this->assertGreaterThan(0, $enemy->getTotalHitPoints(), 'Hit points should be positive');
        $this->assertIsInt($enemy->getArmorClass(), 'Armor class should be an integer');
        $this->assertGreaterThan(0, $enemy->getArmorClass(), 'Armor class should be positive');
        $this->assertGreaterThanOrEqual(1, $enemy->getAttacks());
        $this->assertIsString($enemy->getAttacks()[0]);
    }
}
