<?php

namespace App\Tests\Integration\MonsterCompendium;

use App\EncountersPlanning\TTRPGSystem;
use App\MonsterCompendium\CLI\FillNullVectorEmbeddingsCommand;
use App\MonsterCompendium\Entity\ShadowdarkMonster;
use App\MonsterCompendium\MonsterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class FillNullVectorEmbeddingsCommandTest extends KernelTestCase
{
    private CommandTester $commandTester;
    private MonsterRepository $monsterRepository;
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $this->monsterRepository = $this->entityManager->getRepository(ShadowdarkMonster::class);

        $application = new Application(self::$kernel);
        $command = static::getContainer()->get(FillNullVectorEmbeddingsCommand::class);
        $application->add($command);

        $this->commandTester = new CommandTester($command);
    }

    public function test_it_fills_null_vector_embeddings_for_monsters(): void
    {
        // Create test monsters with null vector embeddings
        $monster = new ShadowdarkMonster(
            level: '1.0',
            name: 'Test Monster 1',
            armorClass: 12,
            attacks: ['Bite'],
            totalHitPoints: 10,
            description: 'This is a test monster with null vector embedding',
        );

        $this->monsterRepository->persist($monster);

        // Execute command
        $this->commandTester->execute([]);

        // Reload monsters from database
        $updatedMonster = $this->monsterRepository->find($monster->getId());

        // Assert
        $this->assertNotNull($updatedMonster, 'Monster 1 should exist in database');

        $this->assertNotNull($updatedMonster->getVectorEmbedding(), 'Monster 1 should have vector embedding generated');

        // Check command output
        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('Completed processing', $output);
    }

    public function test_it_respects_limit_option(): void
    {
        // Create 3 test monsters with null vector embeddings
        for ($i = 1; $i <= 3; $i++) {
            $monster = new ShadowdarkMonster(
                level: '1.0',
                name: "Limited Test Monster $i",
                armorClass: 12,
                attacks: ['Bite'],
                totalHitPoints: 10,
                description: "Test monster $i for limit testing",
            );
            $this->monsterRepository->persist($monster);
        }

        // Execute command with limit=2
        $this->commandTester->execute([
            '--system' => TTRPGSystem::Shadowdark->name,
            '--limit' => 2,
        ]);

        // Count monsters with non-null vector embeddings
        $processedCount = $this->entityManager->createQueryBuilder()
            ->select('COUNT(m)')
            ->from(ShadowdarkMonster::class, 'm')
            ->where('m.name LIKE :pattern')
            ->andWhere('m.vectorEmbedding IS NOT NULL')
            ->setParameter('pattern', 'Limited Test Monster%')
            ->getQuery()
            ->getSingleScalarResult();

        // Assert only 2 monsters were processed
        $this->assertEquals(2, $processedCount, 'Command should respect the limit option');

        // Check command output
        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('Reached processing limit of 2', $output);
    }

    public function test_it_filters_by_system(): void
    {
        // This test assumes multiple monster systems exist
        // Just check the command output for system filtering

        $this->commandTester->execute([
            '--system' => TTRPGSystem::Shadowdark->name,
        ]);

        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('Processing system: ' . TTRPGSystem::Shadowdark->name, $output);
    }
}
