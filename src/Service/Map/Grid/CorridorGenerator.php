<?php

namespace App\Service\Map\Grid;

use App\Helper\Coordinates;
use App\Interface\EnemyGeneratorInterface;
use App\Interface\TreasureGeneratorInterface;

class CorridorGenerator
{
    private TreasureGeneratorInterface $treasureGenerator;
    private EnemyGeneratorInterface $enemyGenerator;
    private float $treasureProbability;
    private float $enemyProbability;

    public function __construct(
        TreasureGeneratorInterface $treasureGenerator,
        EnemyGeneratorInterface $enemyGenerator,
        float $treasureProbability = 0.15,
        float $enemyProbability = 0.15
    ) {
        $this->treasureGenerator = $treasureGenerator;
        $this->enemyGenerator = $enemyGenerator;
        $this->treasureProbability = $treasureProbability;
        $this->enemyProbability = $enemyProbability;
    }

    public function generate(Coordinates $coordinates): Corridor
    {
        $corridor = new Corridor($coordinates);

        // Add treasure with probability
        if (mt_rand() / mt_getrandmax() < $this->treasureProbability) {
            $corridor->setTreasure($this->treasureGenerator->generate());
        }

        // Add enemies with probability
        if (mt_rand() / mt_getrandmax() < $this->enemyProbability) {
            $corridor->setEnemies([$this->enemyGenerator->generate()]);
        }

        return $corridor;
    }
}