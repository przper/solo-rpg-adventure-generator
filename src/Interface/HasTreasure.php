<?php

namespace App\Interface;

interface HasTreasure
{
    public function getTreasure(): ?TreasureInterface;
}
