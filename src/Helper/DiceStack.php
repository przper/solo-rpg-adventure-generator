<?php

namespace App\Helper;

use Symfony\Component\Routing\Exception\InvalidParameterException;

class DiceStack
{
    final public const TYPE_D2 = 'd2';
    final public const TYPE_D6 = 'd6';
    final public const TYPE_D8 = 'd8';
    final public const TYPE_D10 = 'd10';
    final public const TYPE_D12 = 'd12';
    final public const TYPE_D20 = 'd20';

    final public const DICE_STACK_REGEX = "/^(?<count>\d+)?(?<type>d\d{1,2})(?<modifier>[\+-]\d+)?$/";

    private int $dicesCount;

    private string $diceType;

    private int $rollModifier;

    public function getDicesCount(): int
    {
        return $this->dicesCount;
    }

    public function setDicesCount(int $dicesCount): self
    {
        if ($dicesCount < 1) {
            throw new InvalidParameterException("Count must be a positive number.");
        }

        $this->dicesCount = $dicesCount;

        return $this;
    }

    public function getDiceType(): string
    {
        return $this->diceType;
    }

    public function setDiceType(string $diceType): self
    {
        if (! in_array($diceType, self::getTypes())) {
            throw new InvalidParameterException("Not supported Dice Type");
        }

        $this->diceType = $diceType;

        return $this;
    }

    public function getRollModifier(): int
    {
        return $this->rollModifier;
    }

    public function setRollModifier(int $modifier): self
    {
        $this->rollModifier = $modifier;

        return $this;
    }

    public static function getTypes(): array
    {
        return [
            static::TYPE_D2,
            static::TYPE_D6,
            static::TYPE_D8,
            static::TYPE_D10,
            static::TYPE_D12,
            static::TYPE_D20
        ];
    }

    public static function fromIntegers(int $count, int $sides, int $modifier = 0)
    {
        $diceStack = new self();

        $diceStack->setDicesCount($count);
        $diceStack->setDiceType('d'.$sides);
        $diceStack->setRollModifier($modifier);
        
        return $diceStack;
    }

    public static function fromString(string $text): self
    {
        $matches = [];

        preg_match(static::DICE_STACK_REGEX, $text, $matches, PREG_UNMATCHED_AS_NULL);

        if (! count($matches)) {
            throw new InvalidParameterException("Invalid dice text. Accepts: \"d6\", \"1d6\".");
        }

        $diceStack = new self();

        $diceStack->setDicesCount($matches['count'] ?? 1);
        $diceStack->setDiceType($matches['type']);
        $diceStack->setRollModifier($matches['modifier'] ?? 0);

        return $diceStack;
    }

    public function roll(): int
    {
        $result = $this->rollModifier;

        foreach(range(0, $this->dicesCount - 1) as $i) {
            $result += rand(1, $this->getDiceTypeSidesCount());
        }

        return $result;
    }

    private function getDiceTypeSidesCount(): int
    {
        return match ($this->diceType) {
            self::TYPE_D2 => 2,
            self::TYPE_D6 => 6,
            self::TYPE_D8 => 8,
            self::TYPE_D10 => 10,
            self::TYPE_D12 => 12,
            self::TYPE_D20 => 20
        };
    }

    public function __toString()
    {
        return sprintf(
            "%d%s%+d",
            $this->dicesCount,
            $this->diceType,
            $this->rollModifier
        );
    }
}