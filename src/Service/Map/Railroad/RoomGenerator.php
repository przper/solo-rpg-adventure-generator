<?php

namespace App\Service\Map\Railroad;

use App\Helper\Coordinates;
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
    }

    public function generate(Coordinates $coordinates): Room
    {
        $treasure = null;
        $enemies = [];

        if (rand(0, 100) < $this->treasurePercentageChanceInInt) {
            $treasure = $this->treasureGenerator->generate();
        }

        if (rand(0, 100) < $this->enemyPercentageChanceInInt) {
            $enemies = $this->enemyGenerator->generateMany(rand(3, 4));
        }

        return new Room($coordinates, $treasure, $enemies);
    }

    public function starter(): Room
    {
        return new Room(Coordinates::fromIntegers(0, 0));
    }
}
