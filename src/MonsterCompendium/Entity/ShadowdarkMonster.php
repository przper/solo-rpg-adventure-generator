<?php

namespace App\MonsterCompendium\Entity;

use App\MonsterCompendium\ShadowdarkMonsterRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShadowdarkMonsterRepository::class)]
#[ORM\Table(name: 'monster_shadowdark')]
class ShadowdarkMonster extends Monster
{
    public function __construct(
        string $level,
        string $name,
        int $armorClass = 10,
        array $attributes = [],
        array $attacks = [],
        array $specials = [],
        ?int $totalHitPoints = null,
        ?string $description = null,
    ) {
        parent::__construct(
            challengeRating: $level,
            experiencePoints: 0,
            name: $name,
            armorClass: $armorClass,
            attributes: $attributes,
            attacks: $attacks,
            specials: $specials,
            totalHitPoints: $totalHitPoints,
            description: $description,
        );
    }
}
