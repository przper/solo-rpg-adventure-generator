<?php

namespace App\Service\EnemyGenerator;

use App\Helper\DiceStack;
use App\Interface\EnemyInterface;

class Enemy implements EnemyInterface
{
    private float $challangeRating;

    private string $name;

    private DiceStack $hitDice;

    private int $hitPoints;

    private int $armorClass;

    private DiceStack $damage;

    public function getChallangeRating(): float
    {
        return $this->challangeRating;
    }

    public function setChallangeRating(float $challangeRating): self
    {
        $this->challangeRating = $challangeRating;

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

    public function getHitPoints(): int
    {
        return $this->hitPoints;
    }

    public function setHitPoints(int $hitPoints): self
    {
        $this->hitPoints = $hitPoints;

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
            'name' => $this->getName(),
            'hit_dice' => (string) $this->getHitDice(),
            'hit_points' => $this->getHitPoints(),
            'armor_class' => $this->getArmorClass(),
            'damage' => (string) $this->getDamage()
        ];
    }
}