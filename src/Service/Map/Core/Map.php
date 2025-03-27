<?php

namespace App\Service\Map\Core;

use App\Enum\MapDimension;
use App\Helper\Coordinates;

final readonly class Map
{
    /**
     * Aggregate Tiles in a 2D matrix
     *
     * First index: y coordinate (row)
     * Second index: x coordinate (column)
     * Value: ?Tile
     *
     * @var array<array<?Tile>>
     */
    public array $tiles;

    public MapDimension $dimension;

    /** @var MapElement[] $elements */
    public array $elements;

    /**
     * Bind Coordinates to "parent" Element of each Tile. It allows to get Element by providing coordinates
     *
     * key: Coordinate
     * value: Room or Corridor index in $elements
     *
     * @var array<string, string>
     */
    private array $tileParentElement;

    /**
     * @param positive-int $width: x coordinate (column)
     * @param positive-int $height: y coordinate (row)
     * @param array<Room, Corridor> $elements
     */
    public function __construct(
        public int $width,
        public int $height,
        array $elements = [],
    ) {
        $this->guard($elements);

        $this->initialize($elements);
        $this->determineMovementType();
    }

    public function getTile(Coordinates $coordinates): ?Tile
    {
        return $this->tiles[$coordinates->y][$coordinates->x] ?? null;
    }

    /**
     * Skips null (empty) tiles
     * @return Tile[]
     */
    public function getNearbyTiles(Coordinates $coordinates): array
    {
        $nearbyTiles = [];

        $nearbyTiles[] = $this->getTile(Coordinates::fromIntegers($coordinates->x + 1, $coordinates->y));
        $nearbyTiles[] = $this->getTile(Coordinates::fromIntegers($coordinates->x - 1, $coordinates->y));
        $nearbyTiles[] = $this->getTile(Coordinates::fromIntegers($coordinates->x, $coordinates->y + 1));
        $nearbyTiles[] = $this->getTile(Coordinates::fromIntegers($coordinates->x, $coordinates->y - 1));

        return array_values(array_filter($nearbyTiles));
    }

    public function getTilesByType(TileType ...$type): array
    {
        $result = [];

        foreach ($this->tiles as $row) {
            foreach ($row as $tile) {
                if ($tile instanceof Tile && in_array($tile->type, $type)) {
                    $result[] = $tile;
                }
            }
        }

        return $result;
    }

    public function getElementByCoordinates(Coordinates $coordinates): ?MapElement
    {
        $elementId = $this->tileParentElement[(string) $coordinates] ?? null;
        return $this->elements[$elementId] ?? null;
    }

    /** @return Room[] */
    public function getRooms(): array
    {
        return array_values(array_filter($this->elements, fn (MapElement $element) => $element instanceof Room));
    }

    /** @return Corridor[] */
    public function getCorridors(): array
    {
        return array_values(array_filter($this->elements, fn(MapElement $element) => $element instanceof Corridor));
    }

    /** @param array<Room, Corridor> $elements */
    private function initialize(array $elements): void
    {
        $finalTiles = [];
        $tileParentElement = [];

        // create 2D matrix and fill with `null`
        for ($y = 0; $y < $this->height; $y++) {
            $finalTiles[$y] = [];
            for ($x = 0; $x < $this->width; $x++) {
                $finalTiles[$y][$x] = null;
            }
        }

        $tiles = [];

        foreach ($elements as $elementIndex => $element) {
            $tiles = array_merge($tiles, $element->tiles);

            foreach ($element->tiles as $tile) {
                $coordinates = $tile->coordinates;
                $finalTiles[$coordinates->y][$coordinates->x] = $tile;
                $tileParentElement[(string) $coordinates] = $elementIndex;
            }
        }

        $this->tiles = $finalTiles;
        $this->elements = $elements;
        $this->tileParentElement = $tileParentElement;
    }

    /** @param array<Room|Corridor> $elements */
    private function guard(array $elements): void
    {
        if ($this->width <= 0 || $this->height <= 0) {
            throw new \InvalidArgumentException('Wrong dimension, width and height must be greater than 0');
        }

        $uniqueTiles = [];

        foreach ($elements as $element) {
            foreach ($element->tiles as $tile) {
                $key = (string) $tile->coordinates;

                if (array_key_exists($key, $uniqueTiles)) {
                    throw new \InvalidArgumentException("Duplicate Coordinates of $tile->coordinates");
                }

                $uniqueTiles[$key] = true;
            }
        }
    }

    private function determineMovementType(): void
    {
        $movementType = MapDimension::TwoDimension;

        if ($this->width == 1 || $this->height == 1) {
            $movementType = MapDimension::OneDimension;
        }

        $this->dimension = $movementType;
    }
}
