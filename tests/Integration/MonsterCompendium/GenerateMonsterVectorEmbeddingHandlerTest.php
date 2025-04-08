<?php

namespace App\Tests\Integration\MonsterCompendium;

use App\EncountersPlanning\TTRPGSystem;
use App\MonsterCompendium\Command\GenerateMonsterVectorEmbedding\GenerateMonsterVectorEmbeddingCommand;
use App\MonsterCompendium\Command\GenerateMonsterVectorEmbedding\GenerateMonsterVectorEmbeddingHandler;
use App\MonsterCompendium\Entity\ShadowdarkMonster;
use App\MonsterCompendium\ShadowdarkMonsterRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GenerateMonsterVectorEmbeddingHandlerTest extends KernelTestCase
{
    private GenerateMonsterVectorEmbeddingHandler $sut;

    private ShadowdarkMonsterRepository $monsterRepository;

    protected function setUp(): void
    {
        $this->sut = self::getContainer()->get(GenerateMonsterVectorEmbeddingHandler::class);
        $this->monsterRepository = self::getContainer()->get(ShadowdarkMonsterRepository::class);
    }

    public function test_it_sets_embedding_for_given_record(): void
    {
        $monster = new ShadowdarkMonster(
            level: '1.0',
            name: 'Bebok',
            armorClass: 12,
            attacks: ['On drapie'],
            totalHitPoints: 8,
            description: 'Bebok is imaginery monster and it its does not have a settled form',
        );

        $this->monsterRepository->persist($monster);

        $this->assertNotNull($monster->getId());

        call_user_func($this->sut, new GenerateMonsterVectorEmbeddingCommand($monster->getId(), TTRPGSystem::Shadowdark));

        $monster = $this->monsterRepository->find($monster->getId());

        $this->assertNotNull($monster);
        $this->assertNotNull($monster->getVectorEmbedding());
    }
}
