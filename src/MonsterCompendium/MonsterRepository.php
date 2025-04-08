<?php

namespace App\MonsterCompendium;

use App\MonsterCompendium\Entity\Monster;
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
abstract class MonsterRepository extends ServiceEntityRepository
{
    public function __construct(
        private EmbeddingService $embeddingService,
        ManagerRegistry $registry,
        string $entityClass,
    ) {
        parent::__construct($registry, $entityClass);
    }

    public function persist(Monster $monster): void
    {
        if ($monster->getId() === null) {
            $this->getEntityManager()->persist($monster);
        }

        $this->getEntityManager()->flush();
    }

    public function getMatchingByPhrase(string $phrase): array
    {
        $phraseAsVector = $this->embeddingService->generateEmbedding($phrase);
        $stringifiedPhraseVector = "[" . implode(',', $phraseAsVector) . "]";

        $queryForMatchingIds = <<<SQL
            SELECT m.id, (m.vector_embedding <=> :vector) as vector_distance
            FROM {$this->getClassMetadata()->getTableName()} m
            ORDER BY vector_distance ASC -- Lower distance means higher similarity
            LIMIT 10;
        SQL;

        $ids = $this->getEntityManager()->getConnection()->executeQuery($queryForMatchingIds, [
            'vector' => $stringifiedPhraseVector,
        ])->fetchAllAssociative();

        return $this->findBy(['id' => array_column($ids, 'id')]);
    }
}
