<?php

namespace App\Helper;

use JsonSerializable;

final readonly class Coordinates implements JsonSerializable
{
    private function __construct(
        public int $x,
        public int $y,
    ) {
    }

    public static function fromIntegers(int $x, int $y): self
    {
        return new self($x, $y);
    }

    public function moveBy(int $deltaX, int $deltaY): self
    {
        return new self($this->x + $deltaX, $this->y + $deltaY);
    }

    public function isSame(Coordinates $coordinates): bool
    {
        return $this->x === $coordinates->x && $this->y === $coordinates->y;
    }

    public function getDistanceTo(Coordinates $coordinates): float
    {
        return round(sqrt(
            pow($this->x - $coordinates->x, 2) +
            pow($this->y - $coordinates->y, 2)
        ), 3);
    }

    public function jsonSerialize(): mixed
    {
        return [
            'x' => $this->x,
            'y' => $this->y
        ];
    }

    public function __toString(): string
    {
        return sprintf(
            "[%d, %d]",
            $this->x,
            $this->y
        );
    }
}
