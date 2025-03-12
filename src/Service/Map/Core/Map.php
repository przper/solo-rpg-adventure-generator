<?php

namespace App\Service\Map\Core;

use App\Enum\MovementType;
use App\Helper\Coordinates;

final readonly class Map
{
    /**
     * First index: y coordinate (row)
     * Second index: x coordinate (column)
     *
     * @var array<array<?Tile>>
     */
    public array $tiles;

    public MovementType $movementType;

    /**
     * @param positive-int $width: x coordinate (column)
     * @param positive-int $height: y coordinate (row)
     * @param Tile[] $tiles
     */
    public function __construct(
        public int $width,
        public int $height,
        array $tiles = [],
    ) {
        $this->guard();

        $this->initialize($tiles);
        $this->determineMovementType();
    }

    public function getTile(Coordinates $coordinates): ?Tile
    {
        return $this->tiles[$coordinates->getY()][$coordinates->getX()] ?? null;
    }

    public function getTilesByType(TileTypes ...$type): array
    {
        $result = [];

        foreach ($this->tiles as $row) {
            foreach ($row as $tile) {
                if ($tile instanceof Tile && in_array($tile->getType(), $type)) {
                    $result[] = $tile;
                }
            }
        }

        return $result;
    }

    /** @param Tile[] $tiles */
    private function initialize(array $tiles): void
    {
        $finalTiles = [];

        for ($y = 0; $y < $this->height; $y++) {
            $finalTiles[$y] = [];
            for ($x = 0; $x < $this->width; $x++) {
                $finalTiles[$y][$x] = null;
            }
        }

        foreach ($tiles as $tile) {
            $coordinates = $tile->getCoordinates();
            $finalTiles[$coordinates->getY()][$coordinates->getX()] = $tile;
        }

        $this->tiles = $finalTiles;
    }

    private function guard(): void
    {
        if ($this->width <= 0 || $this->height <= 0) {
            throw new \InvalidArgumentException('Wrong dimension, width and height must be greater than 0');
        }
    }

    private function determineMovementType(): void
    {
        $movementType = MovementType::TwoDimension;

        if ($this->width == 1 || $this->height == 1) {
            $movementType = MovementType::OneDimension;
        }

        $this->movementType = $movementType;
    }
}
