<?php

namespace App\MonsterCompendium\Command\GenerateMonsterVectorEmbedding;

use App\EncountersPlanning\TTRPGSystem;
use App\MonsterCompendium\EmbeddingService;
use App\MonsterCompendium\Entity\Monster;
use App\MonsterCompendium\Entity\ShadowdarkMonster;
use App\MonsterCompendium\MonsterRepository;
use Doctrine\ORM\EntityManagerInterface;

final class GenerateMonsterVectorEmbeddingHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private EmbeddingService $embeddingService,
    ) {
    }

    public function __invoke(GenerateMonsterVectorEmbeddingCommand $command): void
    {
        $repository = $this->entityManager->getRepository($this->getEntityForTTRPGSystem($command->system));

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

    /** @return class-string */
    private function getEntityForTTRPGSystem(TTRPGSystem $system): string
    {
        return match ($system) {
            TTRPGSystem::Shadowdark => ShadowdarkMonster::class,
            default => throw new \InvalidArgumentException("No repository for: ".$system->name),
        };
    }
}
