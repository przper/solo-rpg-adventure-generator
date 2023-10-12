<?php

namespace App\Enum;

enum EncounterDifficulty: string
{
    case EASY = 'easy';
    case MEDIUM = 'medium';
    case HARD = 'hard';
    case DEADLY = 'deadly';

    public function getPlayerExperienceTreshold(int $playerLevel): int
    {
        $map = [
            //CHARACTER_LEVEL => [EASY, MEDIUM, HARD, DEADLY]
            1 => ['easy' => 25, 'medium' => 50, 'hard' =>  75, 'deadly' => 100],
            2 => ['easy' => 50, 'medium' => 100, 'hard' =>  150, 'deadly' => 200],
            3 => ['easy' => 75, 'medium' => 150, 'hard' =>  225, 'deadly' => 400],
            4 => ['easy' => 125, 'medium' => 250, 'hard' =>  375, 'deadly' => 500],
            //TO DO: fill the rest
        ];

        return $map[$playerLevel][$this->value];

    }
}
