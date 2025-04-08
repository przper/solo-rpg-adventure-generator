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

    /** @var array<string, string|int> $attributes */
    #[ORM\Column(type: Types::JSON, nullable: false)]
    private array $attributes;

    /** @var string[] $attacks */
    #[ORM\Column(type: Types::JSON, nullable: false)]
    private array $attacks;

    /** @var string[] $specials */
    #[ORM\Column(type: Types::JSON, nullable: false)]
    private array $specials;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description;

    /** @var null|int[] */
    #[ORM\Column(type: VectorEmbeddingType::NAME, nullable: true)]
    private ?array $vectorEmbedding = null;

    /**
     * @param numeric-string $challengeRating
     * @param array<string, string|int> $attributes
     * @param string[] $attacks
     * @param string[] $specials
     */
    public function __construct(
        string $challengeRating,
        int $experiencePoints,
        string $name,
        int $armorClass = 10,
        array $attributes = [],
        array $attacks = [],
        array $specials = [],
        ?int $totalHitPoints = null,
        ?DiceStack $hitDice = null,
        ?string $description = null,
    ) {
        if ($hitDice === null && $totalHitPoints === null) {
            throw new \InvalidArgumentException('Either HitDice or TotalHitPoints must be set.');
        }

        $this->challengeRating = $challengeRating;
        $this->experiencePoints = $experiencePoints;
        $this->name = $name;
        $this->hitDice = $hitDice;
        $this->totalHitPoints = $totalHitPoints ?? $this->hitDice->roll();
        $this->armorClass = $armorClass;
        $this->attributes = $attributes;
        $this->attacks = $attacks;
        $this->specials = $specials;
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

    public function getChallengeRating(): string
    {
        return $this->challengeRating;
    }

    public function getExperiencePoints(): int
    {
        return $this->experiencePoints;
    }

    public function getHitDice(): DiceStack
    {
        return $this->hitDice;
    }

    public function getTotalHitPoints(): ?int
    {
        return $this->totalHitPoints;
    }

    public function getArmorClass(): int
    {
        return $this->armorClass;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getAttacks(): array
    {
        return $this->attacks;
    }

    public function getSpecials(): array
    {
        return $this->specials;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    /** @return null|int[] */
    public function getVectorEmbedding(): ?array
    {
        return $this->vectorEmbedding;
    }

    /** @param null|int[] $vectorEmbedding */
    public function setVectorEmbedding(?array $vectorEmbedding): static
    {
        $this->vectorEmbedding = $vectorEmbedding;

        return $this;
    }
}
