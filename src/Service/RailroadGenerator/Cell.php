<?php

namespace App\Service\RailroadGenerator;

abstract class Cell
{
    private int $x;

    public function getX(): int
    {
        return $this->x;
    }

    public function setX(int $x): self
    {
        $this->x = $x;

        return $this;
    }

    abstract public static function getType(): string;

    abstract public static function getTemplate(): string;
}
