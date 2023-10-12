<?php

namespace App\Service\EncountersPlanner;

use App\Enum\EncounterDifficulty;

class TeamChallengeRating
{
    /** @var int[] */
    private array $teamLevels;

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

    public static function fromLevelsAsIntegers(...$levels): self
    {
        $teamChallengeRating = new self();

        $teamChallengeRating->setTeamLevels($levels);

        return $teamChallengeRating;
    }

    public function getExperienceTresholdForDifficulty(EncounterDifficulty $difficulty): int
    {
        $experienceTreshold = 0;

        foreach ($this->teamLevels as $level) {
            $experienceTreshold += $difficulty->getPlayerExperienceTreshold($level);
        }

        return $experienceTreshold;
    }
}
