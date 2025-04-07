<?php

namespace App\MonsterCompendium;

use App\EncountersPlanning\TTRPGSystem;
use App\MonsterCompendium\Entity\Monster;
use App\MonsterCompendium\Entity\ShadowdarkMonster;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Monster>
 *
 * @method Monster|null find($id, $lockMode = null, $lockVersion = null)
 * @method Monster|null findOneBy(array $criteria, array $orderBy = null)
 * @method Monster[]    findAll()
 * @method Monster[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShadowdarkMonsterRepository extends ServiceEntityRepository implements MonsterRepository
{
    public function __construct(
        private EmbeddingService $embeddingService,
        ManagerRegistry $registry,
    ) {
        parent::__construct($registry, ShadowdarkMonster::class);
    }

    public function supports(): TTRPGSystem
    {
        return TTRPGSystem::Shadowdark;
    }

    public function getMatchingByPhrase(string $phrase): array
    {
        $phraseAsVector = $this->embeddingService->generateEmbedding($phrase);
        $stringifiedPhraseVector = "[" . implode(',', $phraseAsVector) . "]";

        $queryForMatchingIds = <<<SQL
            SELECT m.id, (m.vector_embedding <=> :vector) as vector_distance
            FROM monster_shadowdark m
            ORDER BY vector_distance ASC -- Lower distance means higher similarity
            LIMIT 10;
        SQL;

        $ids = $this->getEntityManager()->getConnection()->executeQuery($queryForMatchingIds, [
            'vector' => $stringifiedPhraseVector,
        ])->fetchAllAssociative();

        return $this->findBy(['id' => array_column($ids, 'id')]);
    }
}
