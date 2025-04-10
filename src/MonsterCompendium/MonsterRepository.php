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

    /**
     * @return Monster[]
     */
    public function get(
        ?float $minChallengeRating = null,
        ?float $maxChallengeRating = null,
        ?string $phrase = null,
    ): array {
        $query = $this->createQueryBuilder('m');

        if ($minChallengeRating !== null) {
            $query
                ->andWhere('m.challengeRating >= :minChallengeRating')
                ->setParameter('minChallengeRating', $minChallengeRating);
        }

        if ($maxChallengeRating !== null) {
            $query
                ->andWhere('m.challengeRating <= :maxChallengeRating')
                ->setParameter('maxChallengeRating', $maxChallengeRating) ;
        }

        if (is_string($phrase)) {
            $phraseAsVector = $this->embeddingService->generateEmbedding($phrase);
            $query
                ->setParameter('vector', $phraseAsVector, 'vector')
                ->orderBy('distance(m.vectorEmbedding, :vector)');
        }

        return $query
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }
}
