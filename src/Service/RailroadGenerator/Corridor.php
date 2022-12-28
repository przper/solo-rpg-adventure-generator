<?php

namespace App\Service\RailroadGenerator;

class Corridor extends Cell
{
    final public const TYPE = 'CORRIDOR';

    public static function getType(): string
    {
        return static::TYPE;
    }

    public static function getTemplate(): string
    {
        return 'map-generator/_corridor.html.twig';
    }

    public static function fromX(int $x): self
    {
        $corridor = new self();

        $corridor->setX($x);

        return $corridor;
    }
}
