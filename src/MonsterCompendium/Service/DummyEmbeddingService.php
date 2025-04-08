<?php

namespace App\MonsterCompendium\Service;

use App\MonsterCompendium\EmbeddingService;

final class DummyEmbeddingService implements EmbeddingService
{
    public function generateEmbedding(string $phrase): array
    {
        $vector = [];

        for ($i = 0; $i < 1536; $i++) {
            $vector[] = random_int(-1000000, 1000000) * 0.00001;
        }

        return $vector;
    }
}
