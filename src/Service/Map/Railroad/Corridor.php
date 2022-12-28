<?php

namespace App\Service\Map\Railroad;

use App\Interface\TreasureInterface;

class Corridor extends Cell
{
    final public const TYPE = 'CORRIDOR';

    private ?TreasureInterface $treasure = null;

    public static function getType(): string
    {
        return static::TYPE;
    }

    public static function getTemplate(): string
    {
        return 'map-generator/_corridor.html.twig';
    }

    public function getTreasure(): ?TreasureInterface
    {
        return $this->treasure;
    }

    public function setTreasure(TreasureInterface $treasure): self
    {
        $this->treasure = $treasure;

        return $this;
    }

    public static function fromX(int $x): self
    {
        $corridor = new self();

        $corridor->setX($x);

        return $corridor;
    }
}
