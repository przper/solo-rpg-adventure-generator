<?php

namespace App\MonsterCompendium\Command\GenerateMonsterVectorEmbedding;

use App\EncountersPlanning\TTRPGSystem;

final readonly class GenerateMonsterVectorEmbeddingCommand
{
    public function __construct(
        public string $monsterId,
        public TTRPGSystem $system,
    ) {
    }
}
