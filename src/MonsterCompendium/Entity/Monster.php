<?php

namespace App\MonsterCompendium\Entity;

use App\Core\Helper\DiceStack;
use App\MonsterCompendium\Doctrine\Type\DiceStackType;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\MappedSuperclass]
abstract class Monster
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: false)]
    private string $name;

    #[ORM\Column(type: Types::DECIMAL, nullable: false)]
    private float $challengeRating;

    #[ORM\Column(type: Types::INTEGER, nullable: false)]
    private int $experiencePoints;

    #[ORM\Column(type: DiceStackType::NAME)]
    private DiceStack $hitDice;

    #[ORM\Column]
    private int $armorClass;

    #[ORM\Column(type: Types::SIMPLE_ARRAY)]
    private array $attacks;

    /** @param string[] $attacks */
    public function __construct(
        int|float $challengeRating,
        int $experiencePoints,
        string $name,
        DiceStack $hitDice,
        int $armorClass = 10,
        array $attacks = [],
    ) {
        $this->challengeRating = (float) $challengeRating;
        $this->experiencePoints = $experiencePoints;
        $this->name = $name;
        $this->hitDice = $hitDice;
        $this->armorClass = $armorClass;
        $this->attacks = $attacks;
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getChallengeRating(): float
    {
        return $this->challengeRating;
    }

    public function setChallengeRating(float $challengeRating): static
    {
        $this->challengeRating = $challengeRating;

        return $this;
    }

    public function getExperiencePoints(): int
    {
        return $this->experiencePoints;
    }

    public function setExperiencePoints(int $experiencePoints): static
    {
        $this->experiencePoints = $experiencePoints;

        return $this;
    }

    public function getHitDice(): DiceStack
    {
        return $this->hitDice;
    }

    public function setHitDice(DiceStack $hitDice): static
    {
        $this->hitDice = $hitDice;

        return $this;
    }

    public function getArmorClass(): int
    {
        return $this->armorClass;
    }

    public function setArmorClass(int $armorClass): static
    {
        $this->armorClass = $armorClass;

        return $this;
    }

    public function getAttacks(): array
    {
        return $this->attacks;
    }

    public function setAttacks(array $attacks): static
    {
        $this->attacks = $attacks;

        return $this;
    }
}
