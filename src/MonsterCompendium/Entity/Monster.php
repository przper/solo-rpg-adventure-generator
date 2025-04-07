<?php

namespace App\MonsterCompendium\Entity;

use App\Core\Helper\DiceStack;
use App\MonsterCompendium\Doctrine\Type\DiceStackType;
use App\MonsterCompendium\Doctrine\Type\VectorEmbeddingType;
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

    #[ORM\Column(type: Types::DECIMAL, nullable: false)]
    private string $challengeRating;

    #[ORM\Column(type: Types::INTEGER, nullable: false)]
    private int $experiencePoints;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: false)]
    private string $name;

    #[ORM\Column(type: DiceStackType::NAME, nullable: true)]
    private ?DiceStack $hitDice = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $totalHitPoints = null;

    #[ORM\Column(type: Types::INTEGER, nullable: false, options: ['default' => 10])]
    private int $armorClass;

    #[ORM\Column(type: Types::JSON, nullable: false)]
    private array $attributes;

    #[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: false)]
    private array $attacks;

    #[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: false)]
    private array $specials;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description;

    #[ORM\Column(type: VectorEmbeddingType::NAME, nullable: true)]
    private ?VectorEmbeddingType $vectorEmbedding = null;

    /** @param string[] $attacks */
    public function __construct(
        int|float $challengeRating,
        int $experiencePoints,
        string $name,
        int $armorClass = 10,
        array $attributes = [],
        array $attacks = [],
        array $special = [],
        ?int $totalHitPoints = null,
        ?DiceStack $hitDice = null,
        ?string $description = null,
    ) {
        if ($hitDice === null && $totalHitPoints === null) {
            throw new \InvalidArgumentException('Either HitDice or TotalHitPoints must be set.');
        }

        $this->challengeRating = (float) $challengeRating;
        $this->experiencePoints = $experiencePoints;
        $this->name = $name;
        $this->hitDice = $hitDice;
        $this->totalHitPoints = $totalHitPoints ?? $this->hitDice->roll();
        $this->armorClass = $armorClass;
        $this->attributes = $attributes;
        $this->attacks = $attacks;
        $this->specials = $special;
        $this->description = $description;
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

    public function getTotalHitPoints(): ?int
    {
        return $this->totalHitPoints;
    }

    public function setTotalHitPoints(?int $totalHitPoints): void
    {
        $this->totalHitPoints = $totalHitPoints;
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

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function setAttributes(array $attributes): static
    {
        $this->attributes = $attributes;

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

    public function getSpecials(): array
    {
        return $this->specials;
    }

    public function setSpecials(array $specials): void
    {
        $this->specials = $specials;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getVectorEmbedding(): ?VectorEmbeddingType
    {
        return $this->vectorEmbedding;
    }

    public function setVectorEmbedding(?VectorEmbeddingType $vectorEmbedding): void
    {
        $this->vectorEmbedding = $vectorEmbedding;
    }
}
