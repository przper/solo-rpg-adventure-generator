<?php

namespace App\MonsterCompendium\Command\GenerateMonsterVectorEmbedding;

use App\MonsterCompendium\EmbeddingService;
use App\MonsterCompendium\Entity\Monster;
use App\MonsterCompendium\MonsterRepository;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

final class GenerateMonsterVectorEmbeddingHandler
{
    /** @var array<string, MonsterRepository> $monsterRepositories */
    private array $monsterRepositories;

    /** @param iterable<MonsterRepository> $monsterRepositories */
    public function __construct(
        #[TaggedIterator('monster_compendium.repository')]
        iterable $monsterRepositories,
        private EmbeddingService $embeddingService,
    ) {
        foreach ($monsterRepositories as $repository) {
            $this->monsterRepositories[$repository->supports()->name] = $repository;
        }
    }

    public function __invoke(GenerateMonsterVectorEmbeddingCommand $command): void
    {
        $repository = $this->monsterRepositories[$command->system->name];

        if (!$repository instanceof MonsterRepository) {
            throw new \InvalidArgumentException("No repository for: ".$command->system->name);
        }

        $monster = $repository->find($command->monsterId);

        if (!$monster instanceof Monster) {
            return;
        }

        $monster->setVectorEmbedding($this
            ->embeddingService
            ->generateEmbedding($this->createFormattedTextForEmbedding($monster))
        );

        $repository->persist($monster);
    }

    private function createFormattedTextForEmbedding(Monster $monster): string
    {
        $parts = [];

        $parts[] = "Monster: ".$monster->getName();
        $parts[] = "Name: ".$monster->getName();

        if (count($monster->getSpecials())) {
            $parts[] = "Specials: " . implode(", ", $monster->getSpecials());
        }

        $parts[] = "Description: " . $monster->getDescription() . " " . $monster->getDescription();

        return implode("\n\n", $parts);
    }
}
