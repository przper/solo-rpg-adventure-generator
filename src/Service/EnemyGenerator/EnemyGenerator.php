<?php

namespace App\Service\EnemyGenerator;

use App\Helper\DiceStack;
use App\Helper\MultipleEnemiesEncounterExperienceCountModifier;
use App\Interface\EnemyInterface;
use App\Interface\EnemyGeneratorInterface;

class EnemyGenerator implements EnemyGeneratorInterface
{
    public function generate(): EnemyInterface
    {
        $enemy = new Enemy();

        $monsterType = array_rand($this->getDnDMonsterManual());
        [$challengeRating, $experiencePoints, $name, $hitDice, $hipPoints, $armorClass, $damage] = $this->getDnDMonsterManual()[$monsterType];

        $enemy->setChallengeRating($challengeRating);
        $enemy->setExperiencePoints($experiencePoints);
        $enemy->setName($name);
        $enemy->setHitDice($hitDice);
        $enemy->setHitPoints($hipPoints ?? $hitDice->roll());
        $enemy->setArmorClass($armorClass);
        $enemy->setDamage($damage);

        return $enemy;
    }

    /**
     * @param int $enemiesCount
     *
     * @return EnemyInterface[]
     */
    public function generateMany(int $enemiesCount): array
    {
        $enemies = [];

        foreach(range(0, $enemiesCount - 1) as $i) {
            $enemies[] = $this->generate();
        }

        return $enemies;
    }

    /**
     * @param int $expectedExperienceSum
     *
     * @return EnemyInterface[]
     */
    public function generateForExperienceNumber(int $expectedExperienceSum): array
    {
        $enemies = [];
        $experienceSum = 0;
        $adjustedExperience = 0;

        while($adjustedExperience < $expectedExperienceSum) {
            /**
             * ToDo:
             * Program encounter "type" - boss lair, patrol, barracks, ambush, single monster, etc.
             */
            $enemy = $this->generate();

            $enemies[] = $enemy;
            $experienceSum += $enemy->getExperiencePoints();
            $adjustedExperience = MultipleEnemiesEncounterExperienceCountModifier::adjustExperiencePoints(count($enemies), $experienceSum);
        }

        return $enemies;
    }

    private function getDnDMonsterManual(): array
    {
        return [
            /** CHALLENGE_RATING, EXPERIENCE_POINTS, NAME, HIT_DICE, HIT_POINTS, ARMOR_CLASS, DAMAGE */
            'DND_5E_GOBLIN_MINION' => [0.25, 10, 'Goblin Minion', DiceStack::fromString("1d6"), 6, '14', DiceStack::fromString("1d2-1")],
            'DND_5E_GOBLIN_WARRIOR' => [0.25, 50, 'Goblin Warrior', DiceStack::fromString("2d6+2"), 9, '15', DiceStack::fromString("1d6+2")],
            'DND_5E_GOBLIN_SPINECLEAVER' => [1, 200, 'Goblin Spinecleaver', DiceStack::fromString("6d6+12"), 33, '14', DiceStack::fromString("1d12+3")],
            'DND_5E_GOBLIN_BOSS' => [2, 450, 'Goblin Boss', DiceStack::fromString("8d6+8"), 36, '17', DiceStack::fromString("1d6+3")],
        ];
    }

    private function getGnGMonsterManual(): array
    {
        return [
            /** CHALLENGE_RATING, EXPERIENCE_POINTS, NAME, HIT_DICE, HIT_POINTS, ARMOR_CLASS, DAMAGE */
            'GOBLIN' => [0.1, 10, 'Goblin', DiceStack::fromString("1d6-1"), null, '13', DiceStack::fromString("1d6")],
            'ORK' => [1, 15*2, 'Ork', DiceStack::fromString("1d6"), null, '13', DiceStack::fromString("1d6")],
            'BLACK_ORK' => [2, 30*2, 'Black Ork', DiceStack::fromString("2d6"), null, '15', DiceStack::fromString("1d6+1")],
            'ORK_BOSS' => [3, 45*4, 'Ork Boss', DiceStack::fromString("3d6"), null, '16', DiceStack::fromString("1d6")],
        ];
    }
}
