<?php

namespace App\Service\Map\Railroad;

use App\Interface\EnemyGeneratorInterface;
use App\Interface\TreasureGeneratorInterface;


class RoomGenerator
{
    private int $treasurePercentageChanceInInt = 50;

    private $enemyPercentageChanceInInt = 80;

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

    public function generate(): Room
    {
        $room = new Room();

        if (rand(0, 100) < $this->treasurePercentageChanceInInt) {
            $treasure = $this->treasureGenerator->generate();
            $room->setTreasure($treasure);
        }

        if (rand(0, 100) < $this->enemyPercentageChanceInInt) {
            $enemies = $this->enemyGenerator->generateMany(rand(3, 4));
            $room->setEnemies($enemies);
        }

        return $room;
    }

}