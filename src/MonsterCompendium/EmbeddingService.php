<?php

namespace App\MonsterCompendium;

interface EmbeddingService
{
    /** @return float[] */
    public function generateEmbedding(string $phrase): array;
}
