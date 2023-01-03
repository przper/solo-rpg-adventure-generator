<?php

namespace App\Helper;

use JsonSerializable;

class Coordinates implements JsonSerializable
{
    private int $x;

    private int $y;

    public function getX(): int
    {
        return $this->x;
    }

    public function setX(int $x): self
    {
        $this->x = $x;

        return $this;
    }

    public function getY(): int
    {
        return $this->y;
    }

    public function setY(int $y): self
    {
        $this->y = $y;

        return $this;
    }

    public function setXY(int $x, int $y): self
    {
        $this->x = $x;
        $this->y = $y;

        return $this;
    }

    public function moveBy(int $deltaX, int $deltaY): self
    {
        $this->x += $deltaX;
        $this->y += $deltaY;

        return $this;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'x' => $this->x,
            'y' => $this->y
        ];
    }

    public static function fromIntegers(int $x, int $y): self
    {
        $coordinates = new self();

        $coordinates->setX($x);
        $coordinates->setY($y);

        return $coordinates;
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