<?php

namespace App\Interface;

use App\Helper\DiceStack;
use JsonSerializable;

interface EnemyInterface extends JsonSerializable
{
    public function getChallengeRating(): float;
    public function getExperiencePoints(): int;
    public function getName(): string;
    public function getHitDice(): DiceStack;
    public function getHitPoints(): int;
    public function getArmorClass(): int;
    public function getDamage(): DiceStack;
}
