<?php

namespace App\Service\EncounterPlanner;

use App\Helper\MultipleEnemiesEncounterExperienceCountModifier;
use App\Interface\EnemyInterface;

class Encounter
{
    final public const DIFFICULTY_EASY = 'easy';
    final public const DIFFICULTY_MEDIUM = 'medium';
    final public const DIFFICULTY_HARD = 'hard';
    final public const DIFFICULTY_DEADLY = 'deadly';

    private string $difficulty;

    /** @var EnemyInterface[] $enemies */
    private array $enemies = [];

    private TeamChallengeRating $challengeRating;

    public function getDifficulty(): string
    {
        return $this->difficulty;
    }

    public function setDifficulty(string $difficulty): self
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    /** @return EnemyInterface[] */
    public function getEnemies(): array
    {
        return $this->enemies;
    }

    /**
     * @param EnemyInterface[] $enemies
     *
     * @return self
     */
    public function setEnemies(array $enemies): self
    {
        $this->enemies = $enemies;

        return $this;
    }

    public function getChallengeRating(): TeamChallengeRating
    {
        return $this->challengeRating;
    }

    public function setChallengeRating(TeamChallengeRating $challengeRating): self
    {
        $this->challengeRating = $challengeRating;

        return $this;
    }

    public function getRawEnemiesExperienceSum(): int
    {
        return array_reduce($this->enemies, fn ($c, EnemyInterface $e) => $c+$e->getExperiencePoints());
    }

    public function getAdjustedEnemiesExperienceSum(): int
    {
        return MultipleEnemiesEncounterExperienceCountModifier::adjustExperiencePoints(
            count($this->enemies),
            $this->getRawEnemiesExperienceSum()
        );
    }
}
