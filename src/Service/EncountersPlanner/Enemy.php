<?php

namespace App\Service\EncountersPlanner;

use App\Helper\DiceStack;

final class Enemy
{
    public function __construct(
        private float $challengeRating,
        private int $experiencePoints,
        private string $name,
        private DiceStack $hitDice,
        private int $totalHitPoints,
        private int $armorClass,
        private DiceStack $damage,
    ) {
    }

    public function getChallengeRating(): float
    {
        return $this->challengeRating;
    }

    public function setChallengeRating(float $challengeRating): self
    {
        $this->challengeRating = $challengeRating;

        return $this;
    }

    public function getExperiencePoints(): int
    {
        return $this->experiencePoints;
    }

    public function setExperiencePoints(int $experiencePoints): self
    {
        $this->experiencePoints = $experiencePoints;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getHitDice(): DiceStack
    {
        return $this->hitDice;
    }

    public function setHitDice(DiceStack $hitDice): self
    {
        $this->hitDice = $hitDice;

        return $this;
    }

    public function getTotalHitPoints(): int
    {
        return $this->totalHitPoints;
    }

    public function setTotalHitPoints(int $totalHitPoints): self
    {
        $this->totalHitPoints = $totalHitPoints;

        return $this;
    }

    public function getArmorClass(): int
    {
        return $this->armorClass;
    }

    public function setArmorClass(int $armorClass): self
    {
        $this->armorClass = $armorClass;

        return $this;
    }

    public function getDamage(): DiceStack
    {
        return $this->damage;
    }

    public function setDamage(DiceStack $damage): self
    {
        $this->damage = $damage;

        return $this;
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
            'damage' => (string) $this->getDamage()
        ];
    }
}
