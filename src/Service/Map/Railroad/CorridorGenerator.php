<?php

namespace App\Service\Map\Railroad;

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

    public function setTreasurePercentageChanceInInt(int $treasurePercentageChanceInInt): self
    {
        $this->treasurePercentageChanceInInt = $treasurePercentageChanceInInt;

        return $this;
    }

    public function setEnemyPercentageChanceInInt(int $enemyPercentageChanceInInt): self
    {
        $this->enemyPercentageChanceInInt = $enemyPercentageChanceInInt;

        return $this;
    }

    public function generate(): Corridor
    {
        $corridor = new Corridor();

        if (rand(0, 100) < $this->treasurePercentageChanceInInt) {
            $treasure = $this->treasureGenerator->generate();
            $corridor->setTreasure($treasure);
        }

        if (rand(0, 100) < $this->enemyPercentageChanceInInt) {
            $enemies = $this->enemyGenerator->generateMany(rand(1, 3));
            $corridor->setEnemies($enemies);
        }

        $this->setTreasurePercentageChanceInInt(20);
        $this->setEnemyPercentageChanceInInt(30);

        return $corridor;
    }
}