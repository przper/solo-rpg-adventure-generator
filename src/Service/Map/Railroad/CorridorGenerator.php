<?php

namespace App\Service\Map\Railroad;

use App\Helper\Coordinates;
use App\Interface\EnemyGeneratorInterface;
use App\Interface\TreasureGeneratorInterface;

class CorridorGenerator
{
    private int $treasurePercentageChanceInInt = 20;

    private $enemyPercentageChanceInInt = 30;

    public function __construct(
        private TreasureGeneratorInterface $treasureGenerator,
        private EnemyGeneratorInterface $enemyGenerator
    ) {
        //
    }

    public function generate(Coordinates $coordinates): Corridor
    {
        $treasure = null;
        $enemies = [];

        if (rand(0, 100) < $this->treasurePercentageChanceInInt) {
            $treasure = $this->treasureGenerator->generate();
        }

        if (rand(0, 100) < $this->enemyPercentageChanceInInt) {
            $enemies = $this->enemyGenerator->generateMany(rand(1, 3));
        }

        return new Corridor($coordinates, $treasure, $enemies);
    }
}
