<?php

namespace App\Service\Map\Roguelike;

use App\Helper\Coordinates;
use App\Interface\HasEnemies;
use App\Interface\HasTreasure;
use App\Interface\MapCellInterface;
use App\Interface\TreasureInterface;

class Room implements MapCellInterface, HasTreasure, HasEnemies
{
    final public const TYPE = 'ROOM';

    private ?TreasureInterface $treasure = null;

    public function __construct(
        private Coordinates $coordinates
    ) {
        //
    }

    public function getCoordinates(): Coordinates
    {
        return $this->coordinates;
    }

    public function getType(): string
    {
        return self::TYPE;
    }

    public function getTreasure(): ?TreasureInterface
    {
        return $this->treasure;
    }

    public function getEnemies(): array
    {
        return [];
    }

    public static function createWithRandomCoordinates(int $maxX, int $maxY): self
    {
        $coordinates = Coordinates::fromIntegers(rand(0, $maxX), rand(0, $maxY));

        $room = new self($coordinates);

        return $room;
    }
}
