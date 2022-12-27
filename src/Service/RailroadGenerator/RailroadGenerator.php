<?php

namespace App\Service\RailroadGenerator;

use App\Service\MapGeneratorInterface;
use App\Service\MapInterface;
use Twig\Environment;

class RailroadGenerator implements MapGeneratorInterface
{
    public function __construct(
        private Environment $twig
    ) {
        //
    }
    public function create(int $rowsCount, int $columnsCount, int $roomsCount): MapInterface
    {
        $map = new Map($rowsCount, $roomsCount);

        return $map;
    }
}
