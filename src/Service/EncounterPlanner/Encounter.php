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
    
    private int $challangeRating;

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

    public function getChallangeRating(): int
    {
        return $this->challangeRating;
    }

    public function setChallangeRating(int $challangeRating): self
    {
        $this->challangeRating = $challangeRating;

        return $this;
    }

    public static function create(string $difficulty, int $teamChallangeRating): self
    {
        $encounter = new self();

        $enemiesChallangeRatingSum = $teamChallangeRating * match($difficulty) {
            self::DIFFICULTY_EASY => 0.5,
            self::DIFFICULTY_MEDIUM => 1,
            self::DIFFICULTY_HARD => 1.5,
            self::DIFFICULTY_DEADLY => 2.5
        };

        $encounter->setDifficulty($difficulty);
        $encounter->setChallangeRating($enemiesChallangeRatingSum);

        return $encounter;
    }
}
