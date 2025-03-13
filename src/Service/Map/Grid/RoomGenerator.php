<?php

namespace App\Service\Map\Grid;

use App\Helper\Coordinates;
use App\Interface\EnemyGeneratorInterface;
use App\Interface\TreasureGeneratorInterface;

final readonly class RoomGenerator
{
    public function __construct(
        private TreasureGeneratorInterface $treasureGenerator,
        private EnemyGeneratorInterface $enemyGenerator,
        private float $treasureProbability = 0.3,
        private float $enemyProbability = 0.3
    ) {
    }

    public function generate(Coordinates $coordinates): Room
    {
        // Generate treasure based on probability
        $treasure = null;
        if (mt_rand() / mt_getrandmax() < $this->treasureProbability) {
            $treasure = $this->treasureGenerator->generate();
        }

        // Generate enemies based on probability
        $enemies = [];
        if (mt_rand() / mt_getrandmax() < $this->enemyProbability) {
            $enemies = [$this->enemyGenerator->generate()];
        }

        // Create immutable room with all necessary properties
        return new Room($coordinates, $treasure, $enemies);
    }
}