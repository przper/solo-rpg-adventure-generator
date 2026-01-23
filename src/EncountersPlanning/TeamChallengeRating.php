<?php

namespace App\EncountersPlanning;

use IteratorAggregate;
use Traversable;

/**
 * @implements IteratorAggregate<int, positive-int>
 */
readonly class TeamChallengeRating implements IteratorAggregate
{
    /** @param positive-int[] $teamLevels */
    final public function __construct(
        private array $teamLevels,
    ) {
    }

    public static function fromLevelsAsIntegers(...$levels): self
    {
        return new static($levels);
    }

    public function getAveragePlayerLevel(): int
    {
        if ($this->teamLevels === []) {
            return 0;
        }

        return floor(array_sum($this->teamLevels) / count($this->teamLevels));
    }

    public function getIterator(): Traversable
    {
        return new \ArrayIterator(array_values($this->teamLevels));
    }

    public function toArray(): array
    {
        return $this->teamLevels;
    }
}
