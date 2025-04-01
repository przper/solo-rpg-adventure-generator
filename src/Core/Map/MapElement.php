<?php

namespace App\Core\Map;

abstract readonly class MapElement
{
    /** @param Tile[] $tiles */
    protected function __construct(
        public array $tiles = [],
    ) {
        $this->guard();
    }

    abstract public static function create(array $coordinates): static;

    protected function guard(): void
    {
        $coordinates = array_map(fn(Tile $tile) => $tile->coordinates, $this->tiles);
        if (count($coordinates) !== count(array_unique($coordinates, SORT_REGULAR))) {
            throw new \InvalidArgumentException('Duplicate coordinates found in the provided tiles.');
        }
    }
}
