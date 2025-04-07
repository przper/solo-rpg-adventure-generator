<?php

namespace App\MonsterCompendium\Doctrine\Type;

use App\Core\Helper\DiceStack;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class DiceStackType extends Type
{
    public const NAME = 'dice_stack';

    /**
     * Gets the SQL declaration snippet for a field of this type.
     */
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    /**
     * Converts a value from its database representation to its PHP representation.
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?DiceStack
    {
        if ($value === null || $value === '') {
            return null;
        }

        return DiceStack::fromString($value);
    }

    /**
     * Converts a value from its PHP representation to its database representation.
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof DiceStack) {
            return (string) $value;
        }

        throw new \InvalidArgumentException(
            "Value must be an instance of " . DiceStack::class
        );
    }

    /**
     * Gets the name of this type.
     */
    public function getName(): string
    {
        return self::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
