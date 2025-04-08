<?php

namespace App\MonsterCompendium\Service;

use App\MonsterCompendium\EmbeddingService;
use OpenAI\Client;
use Psr\Log\LoggerInterface;

class OpenAiEmbeddingService implements EmbeddingService
{
    public final const MODEL_NAME = 'text-embedding-3-small';

    public function __construct(
        private Client $client,
        private LoggerInterface $logger,
    ) {
    }

    /** @return float[] */
    public function generateEmbedding(string $phrase): array
    {
        $response = $this->client->embeddings()->create([
            'model' => self::MODEL_NAME,
            'input' => $phrase,
        ]);

        $this->logger->info(json_encode($response->toArray()));

        // The response contains embedding vectors in data[0].embedding
        return $response->embeddings[0]->embedding;
    }
}
