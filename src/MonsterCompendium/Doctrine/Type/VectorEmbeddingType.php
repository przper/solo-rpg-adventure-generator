<?php

namespace App\MonsterCompendium\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class VectorEmbeddingType extends Type
{
    public const NAME = 'vector_embedding';
    private const DEFAULT_DIMENSION = 1536; // Default for OpenAI embeddings

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        $dimension = $column['dimension'] ?? self::DEFAULT_DIMENSION;
        return "vector($dimension)";
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?array
    {
        if ($value === null) {
            return null;
        }

        // Remove the vector formatting and convert to PHP array
        $value = trim($value, '[]');
        return array_map('floatval', explode(',', $value));
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return null;
        }

        // Ensure $value is an array
        if (!is_array($value)) {
            throw new \InvalidArgumentException('Vector value must be an array of floats');
        }

        // Convert PHP array to PostgreSQL vector format
        return '[' . implode(',', array_map('strval', $value)) . ']';
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
