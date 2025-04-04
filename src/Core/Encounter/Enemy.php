<?php

namespace App\Core\Encounter;

use App\Core\Helper\DiceStack;

final class Enemy
{
    private EnemyStatus $status = EnemyStatus::Alive;
    private int $totalHitPoints;

    /** @param string[] $attacks */
    public function __construct(
        private float $challengeRating,
        private int $experiencePoints,
        private string $name,
        private DiceStack $hitDice,
        private int $armorClass = 10,
        private array $attacks = [],
        ?int $totalHitPoints = null,
    ) {
        $this->totalHitPoints = $totalHitPoints ?? $this->hitDice->roll();
    }

    public function slay(): void
    {
        $this->status = EnemyStatus::Slain;
    }

    public function isAlive(): bool
    {
        return $this->status === EnemyStatus::Alive;
    }

    public function getChallengeRating(): float
    {
        return $this->challengeRating;
    }

    public function getExperiencePoints(): int
    {
        return $this->experiencePoints;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getHitDice(): DiceStack
    {
        return $this->hitDice;
    }

    public function getTotalHitPoints(): int
    {
        return $this->totalHitPoints;
    }

    public function getArmorClass(): int
    {
        return $this->armorClass;
    }

    /** @return string[] */
    public function getAttacks(): array
    {
        return $this->attacks;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'challenge_rating' => $this->challengeRating,
            'experience_points' => $this->experiencePoints,
            'name' => $this->getName(),
            'hit_dice' => (string) $this->getHitDice(),
            'hit_points' => $this->getTotalHitPoints(),
            'armor_class' => $this->getArmorClass(),
            'damage' => $this->getAttacks(),
        ];
    }
}
