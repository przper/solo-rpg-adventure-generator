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
        [$name, $hitDice, $armorClass, $damage] = $this->getMonsterManual()[$monsterType];

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
            /** NAME, HIT_DICE, ARMOR_CLASS, DAMAGE */
            'GOBLIN' => ['Goblin', DiceStack::fromString("1d6"), '13', DiceStack::fromString("1d6")],
            'GOBLIN_BOSS' => ['Goblin Boss', DiceStack::fromString("6d6"), '17', DiceStack::fromString("1d6+2")],
        ];
    }
}