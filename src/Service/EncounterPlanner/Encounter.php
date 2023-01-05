<?php

namespace App\Service\EncounterPlanner;

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
    
    private TeamChallangeRating $challangeRating;

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

    public function getChallangeRating(): TeamChallangeRating
    {
        return $this->challangeRating;
    }

    public function setChallangeRating(TeamChallangeRating $challangeRating): self
    {
        $this->challangeRating = $challangeRating;

        return $this;
    }
}
