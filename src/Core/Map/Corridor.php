<?php

namespace App\Core\Map;

final readonly class Corridor extends MapElement
{
    public static function create(array $coordinates): static
    {
        $tiles = [];

        foreach ($coordinates as $coordinate) {
            $tiles[] = new Tile($coordinate, TileType::Corridor);
        }

        return new self($tiles);
    }
}
