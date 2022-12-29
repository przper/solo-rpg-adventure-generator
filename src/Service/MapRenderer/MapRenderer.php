<?php

namespace App\Service\MapRenderer;

use App\Interface\MapCellInterface;
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
        $cells = $map->getCells();

        array_walk_recursive($cells, function (MapCellInterface $cell) {
            $cell->template = $this->resolveCellTemplate($cell);
        });

        return $this->twig->render('map/map.html.twig', [
            'cells' => $cells,
            'player_position' => $position
        ]);
    }

    private function resolveCellTemplate(MapCellInterface $cell): string
    {
        return match ($cell->getType()) {
            'ROOM' => 'map/_room.html.twig',
            'CORRIDOR' => 'map/_corridor.html.twig',
            'WALL' => 'map/_wall.html.twig',
        };
    }
}
