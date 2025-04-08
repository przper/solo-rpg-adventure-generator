<?php

namespace App\MonsterCompendium\CLI;

use App\EncountersPlanning\TTRPGSystem;
use App\MonsterCompendium\Command\GenerateMonsterVectorEmbedding\GenerateMonsterVectorEmbeddingCommand;
use App\MonsterCompendium\Command\GenerateMonsterVectorEmbedding\GenerateMonsterVectorEmbeddingHandler;
use App\MonsterCompendium\Entity\ShadowdarkMonster;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:monster:fill-null-vector-embeddings',
    description: 'Find monsters with null vector_embedding and generate them'
)]
class FillNullVectorEmbeddingsCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private GenerateMonsterVectorEmbeddingHandler $handler
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('limit', 'l', InputOption::VALUE_OPTIONAL, 'Limit the number of monsters to process', null)
            ->addOption('system', 's', InputOption::VALUE_OPTIONAL, 'Process only monsters from specific system', null);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $limit = $input->getOption('limit') ? (int) $input->getOption('limit') : null;
        $systemFilter = $input->getOption('system');

        $io->title('Filling null vector embeddings for monsters');

        $totalProcessed = 0;

        foreach (TTRPGSystem::cases() as $systemName) {
            // Skip if a system filter is specified and doesn't match
            if ($systemFilter !== null && $systemFilter !== $systemName->name) {
                continue;
            }

            $io->section("Processing system: $systemName->name");

            $entityClassName = match ($systemName) {
                TTRPGSystem::Shadowdark => ShadowdarkMonster::class,
                default => null,
            };
            if ($entityClassName === null) {
                $io->warning("No repository for: $systemName->name");
                continue;
            }

            $repository = $this->entityManager->getRepository($entityClassName);

            // Find monsters with null vector_embedding
            $monsters = $repository->findBy(['vectorEmbedding' => null]);

            if (empty($monsters)) {
                $io->info("No monsters with null vector_embedding found for $systemName->name");
                continue;
            }

            $systemLimit = $limit;
            $io->progressStart(min(count($monsters), $systemLimit ?: count($monsters)));

            foreach ($monsters as $monster) {
                if ($systemLimit !== null && $totalProcessed >= $systemLimit) {
                    break;
                }

                try {
                    $command = new GenerateMonsterVectorEmbeddingCommand(
                        $monster->getId(),
                        $systemName,
                    );

                    ($this->handler)($command);

                    $io->progressAdvance();
                    $totalProcessed++;
                } catch (\Exception $e) {
                    $io->error("Error processing monster {$monster->getName()}: {$e->getMessage()}");
                }
            }

            $io->progressFinish();
            $io->success("Processed $totalProcessed monsters for $systemName->name");

            if ($limit !== null && $totalProcessed >= $limit) {
                $io->info("Reached processing limit of $limit monsters");
                break;
            }
        }

        $io->success("Completed processing $totalProcessed monsters");

        return Command::SUCCESS;
    }
}
