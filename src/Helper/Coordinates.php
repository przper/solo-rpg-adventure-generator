<?php

namespace App\Helper;

use JsonSerializable;

final readonly class Coordinates implements JsonSerializable
{
    private function __construct(
        private int $x,
        private int $y,
    ) {
    }

    public static function fromIntegers(int $x, int $y): self
    {
        return new self($x, $y);
    }

    public function getX(): int
    {
        return $this->x;
    }

    public function getY(): int
    {
        return $this->y;
    }

    public function moveBy(int $deltaX, int $deltaY): self
    {
        return new self($this->x + $deltaX, $this->y + $deltaY);
    }

    public function isSame(Coordinates $coordinates): bool
    {
        return $this->x === $coordinates->x && $this->y === $coordinates->y;
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
