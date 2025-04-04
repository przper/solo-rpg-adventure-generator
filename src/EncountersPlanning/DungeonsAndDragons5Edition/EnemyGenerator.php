<?php

namespace App\EncountersPlanning\DungeonsAndDragons5Edition;

use App\Core\Helper\DiceStack;
use App\EncountersPlanning\Enemy;

class EnemyGenerator
{
    public function generate(): Enemy
    {
        $monsterName = array_rand($this->getDnDMonsterManual());
        [$challengeRating, $experiencePoints, $name, $hitDice, $hitPoints, $armorClass, $damage] = $this->getDnDMonsterManual()[$monsterName];

        return new Enemy(
            challengeRating: $challengeRating,
            experiencePoints: $experiencePoints,
            name: $name,
            hitDice: $hitDice,
            armorClass: $armorClass,
            damage: $damage,
            totalHitPoints: $hitPoints,
        );
    }

    private function getDnDMonsterManual(): array
    {
        return [
            /** CHALLENGE_RATING, EXPERIENCE_POINTS, NAME, HIT_DICE, HIT_POINTS, ARMOR_CLASS, DAMAGE */
            'DND_5E_GOBLIN_MINION' => [0.125, 10, 'Goblin Minion', DiceStack::fromString("1d6"), 6, '14', DiceStack::fromString("1d2-1")],
            'DND_5E_GOBLIN_WARRIOR' => [0.25, 50, 'Goblin Warrior', DiceStack::fromString("2d6+2"), 9, '15', DiceStack::fromString("1d6+2")],
            'DND_5E_GOBLIN_SPINECLEAVER' => [1, 200, 'Goblin Spinecleaver', DiceStack::fromString("6d6+12"), 33, '14', DiceStack::fromString("1d12+3")],
            'DND_5E_GOBLIN_BOSS' => [2, 450, 'Goblin Boss', DiceStack::fromString("8d6+8"), 36, '17', DiceStack::fromString("1d6+3")],
        ];
    }
}
