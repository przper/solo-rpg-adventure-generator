<?php

namespace App\Interface;

interface MapInterface
{
    public function getRooms(): array;

    public function getCells(): array;
}
