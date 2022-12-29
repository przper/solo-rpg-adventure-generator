<?php

namespace App\Service\MapRenderer;

use App\Interface\MapInterface;
use App\Service\Game\PlayerPosition;
use Twig\Environment;

class MapRenderer
{
    public function __construct(
        private Environment $twig
    ) {
        //
    }

    public function render(MapInterface $map, PlayerPosition $position)
    {
        return $this->twig->render('map/map.html.twig', [
            'map' => $map,
            'player_position' => $position
        ]);
    }
}
