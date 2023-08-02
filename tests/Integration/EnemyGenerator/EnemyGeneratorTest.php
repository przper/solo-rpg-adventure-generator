<?php

namespace App\Tests\Integration\EnemyGenerator;

use App\Helper\MultipleEnemiesEncounterExperienceCountModifier;
use App\Interface\EnemyInterface;
use App\Service\EnemyGenerator\Enemy;
use App\Service\EnemyGenerator\EnemyGenerator;
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
        $this->assertInstanceOf(Enemy::class, $this->generator->generate());
    }

    public function test_it_generates_many_for_given_count()
    {
        $count = 10;
        $enemies = $this->generator->generateMany($count);

        $this->assertCount($count, $enemies);
        $this->assertInstanceOf(Enemy::class, $enemies[0]);
    }

    public function test_it_generates_many_for_given_experience_number()
    {
        $adjustedExperienceTreshold = 1000;
        $enemies = $this->generator->generateForExperienceNumber($adjustedExperienceTreshold);

        $this->assertInstanceOf(Enemy::class, $enemies[0]);
        $rawEnemiesExperiencePointsCount = array_reduce($enemies, fn($c, EnemyInterface $e) => $c+$e->getExperiencePoints());
        $adjustedExperienceTreshold = MultipleEnemiesEncounterExperienceCountModifier::adjustExperiencePoints(count($enemies), $rawEnemiesExperiencePointsCount);

        $this->assertGreaterThanOrEqual(1000, $adjustedExperienceTreshold);
        $this->assertInstanceOf(Enemy::class, $enemies[0]);
    }
}
