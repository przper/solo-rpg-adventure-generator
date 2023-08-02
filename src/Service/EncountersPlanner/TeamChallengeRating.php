<?php

namespace App\Service\EncountersPlanner;

class TeamChallengeRating
{
    final public const DIFFICULTY_EASY = 'easy';
    final public const DIFFICULTY_MEDIUM = 'medium';
    final public const DIFFICULTY_HARD = 'hard';
    final public const DIFFICULTY_DEADLY = 'deadly';

    private array $teamLevels;

    /** @return int[] */
    public function getTeamLevels(): array
    {
        return $this->teamLevels;
    }

    /**
     * @param int[] $teamLevels
     *
     * @return self
     */
    public function setTeamLevels(array $teamLevels): self
    {
        $this->teamLevels = $teamLevels;

        return $this;
    }

    public static function getPlayerExperienceTresholdByDifficulty(string $difficulty, int $level): int
    {
        $map = [
            //CHARACTER_LEVEL => [EASY, MEDIUM, HARD, DEADLY]
            1 => ['easy' => 25, 'medium' => 50, 'hard' =>  75, 'deadly' => 100],
            2 => ['easy' => 50, 'medium' => 100, 'hard' =>  150, 'deadly' => 200],
            3 => ['easy' => 75, 'medium' => 150, 'hard' =>  225, 'deadly' => 400],
            4 => ['easy' => 125, 'medium' => 250, 'hard' =>  375, 'deadly' => 500],
            //TO DO: fill the rest
        ];

        return $map[$level][$difficulty];
    }

    public static function fromLevelsAsIntegers(...$levels): self
    {
        $teamChallengeRating = new self();

        $teamChallengeRating->setTeamLevels($levels);

        return $teamChallengeRating;
    }

    public function getExperienceTresholdForDifficulty(string $difficulty): int
    {
        $experienceTreshold = 0;

        foreach ($this->teamLevels as $level) {
            $experienceTreshold += static::getPlayerExperienceTresholdByDifficulty($difficulty, $level);
        }

        return $experienceTreshold;
    }
}
