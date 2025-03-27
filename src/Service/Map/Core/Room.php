<?php

namespace App\Service\Map\Core;

final readonly class Room extends MapElement
{
    public static function create(array $coordinates): static
    {
        $tiles = [];

        foreach ($coordinates as $coordinate) {
            $tiles[] = new Tile($coordinate, TileType::Room);
        }

        return new self($tiles);
    }
}
