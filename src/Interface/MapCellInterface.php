<?php

namespace App\Interface;

interface MapCellInterface
{
    public function getXCoordinate(): int;

    public function getYCoordinate(): int;

    public function getType(): string;
}