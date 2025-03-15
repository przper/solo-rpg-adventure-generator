<?php

namespace App\Service\Map\Core;

final readonly class Corridor
{
    /** @param Tile[] $tiles */
    private function __construct(
        public array $tiles = [],
    ) {
        $this->guard();
    }

    public static function create(array $coordinates): self
    {
        $tiles = [];

        foreach ($coordinates as $coordinate) {
            $tiles[] = new Tile($coordinate, TileType::Corridor);
        }

        return new self($tiles);
    }

    private function guard(): void
    {
        $coordinates = array_map(fn(Tile $tile) => $tile->coordinates, $this->tiles);
        if (count($coordinates) !== count(array_unique($coordinates, SORT_REGULAR))) {
            throw new \InvalidArgumentException('Duplicate coordinates found in the provided tiles.');
        }
    }
}
