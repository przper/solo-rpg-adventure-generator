<?php

namespace App\MonsterCompendium;

use App\MonsterCompendium\Entity\ShadowdarkMonster;
use Doctrine\Persistence\ManagerRegistry;

class ShadowdarkMonsterRepository extends MonsterRepository
{
    public function __construct(EmbeddingService $embeddingService, ManagerRegistry $registry)
    {
        parent::__construct($embeddingService, $registry, ShadowdarkMonster::class);
    }
}
