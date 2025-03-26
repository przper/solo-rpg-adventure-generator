<?php

namespace App\Service\EncountersPlanner;

use IteratorAggregate;
use Traversable;

readonly class TeamChallengeRating implements IteratorAggregate
{
    /** @param int[] $teamLevels */
    final public function __construct(
        protected array $teamLevels,
    ) {
    }

    public static function fromLevelsAsIntegers(...$levels): self
    {
        return new static($levels);
    }

    public function getIterator(): Traversable
    {
        return new \ArrayIterator(array_values($this->teamLevels));
    }
}
