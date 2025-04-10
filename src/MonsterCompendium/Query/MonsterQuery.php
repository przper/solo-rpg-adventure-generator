<?php

namespace App\MonsterCompendium\Query;

use App\EncountersPlanning\TTRPGSystem;
use App\MonsterCompendium\Entity\Monster;
use App\MonsterCompendium\Entity\ShadowdarkMonster;
use Doctrine\ORM\EntityManagerInterface;

final class MonsterQuery
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @return Monster[]
     */
    public function __invoke(MonsterQueryParams $params): array
    {
        $entityClassName = match ($params->system) {
            TTRPGSystem::Shadowdark => ShadowdarkMonster::class,
            default => null,
        };

        if ($entityClassName === null) {
            return [];
        }

        $repository = $this->entityManager->getRepository($entityClassName);

        if ($params->phrase != null) {
            return $repository->get(phrase: $params->phrase);
        }

        return $repository->findAll();
    }
}
