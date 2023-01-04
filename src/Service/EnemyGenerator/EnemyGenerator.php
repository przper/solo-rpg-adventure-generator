<?php

namespace App\Service\EnemyGenerator;

use App\Helper\DiceStack;
use App\Interface\EnemyInterface;
use App\Interface\EnemyGeneratorInterface;

class EnemyGenerator implements EnemyGeneratorInterface
{
    public function generate(): EnemyInterface
    {
        $enemy = new Enemy();

        $monsterType = array_rand($this->getMonsterManual());
        [$challangeRating, $name, $hitDice, $armorClass, $damage] = $this->getMonsterManual()[$monsterType];

        $enemy->setChallangeRating($challangeRating);
        $enemy->setName($name);
        $enemy->setHitDice($hitDice);
        $enemy->setHitPoints($hitDice->roll());
        $enemy->setArmorClass($armorClass);
        $enemy->setDamage($damage);

        return $enemy;
    }

    public function generateMany(int $enemiesCount): array
    {
        $enemies = [];

        foreach(range(0, $enemiesCount - 1) as $i) {
            $enemies[] = $this->generate();
        }

        return $enemies;
    }

    private function getMonsterManual(): array
    {
        return [
            /** CHALLANGE_RATING, NAME, HIT_DICE, ARMOR_CLASS, DAMAGE */
            'GOBLIN' => [1, 'Goblin', DiceStack::fromString("1d6"), '13', DiceStack::fromString("1d6")],
            'GOBLIN_WARRIOR' => [2, 'Goblin Warrior', DiceStack::fromString("2d6"), '13', DiceStack::fromString("1d6+2")],
            'GOBLIN_BOSS' => [3, 'Goblin Boss', DiceStack::fromString("3d6"), '15', DiceStack::fromString("1d6+2")],
        ];
    }
}