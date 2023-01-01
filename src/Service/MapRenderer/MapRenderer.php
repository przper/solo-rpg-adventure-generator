<?php

namespace App\Service\MapRenderer;

use App\Interface\MapCellInterface;
use App\Interface\MapInterface;
use App\Service\Game\Game;
use Twig\Environment;

class MapRenderer
{
    public function __construct(
        private Environment $twig
    ) {
        //
    }

    public function render(MapInterface $map, ?Game $game = null)
    {
        $cells = $map->getCells();

        if (! is_null($game)) {
            array_walk_recursive($cells, function (MapCellInterface $cell) use ($game) {
                $cell->has_player = false;
                $cell->is_visited = false;

                $position = $game->getPlayerPosition();

                if ($cell->getXCoordinate() === $position->getX() && $cell->getYCoordinate() === $position->getY()) {
                    $cell->has_player = true;
                }

                $cellCoordinates = ['x'=> $cell->getXCoordinate(), 'y' => $cell->getYCoordinate()];
                if (in_array($cellCoordinates, $game->getVisitedCells())) {
                    $cell->is_visited = true;
                }

                $cell->template = $this->resolveCellTemplate($cell);
            });
        }

        return $this->twig->render('map/map.html.twig', [
            'cells' => $cells,
        ]);
    }

    private function resolveCellTemplate(MapCellInterface $cell): string
    {
        if (! $cell->is_visited) {
            return 'map/_wall.html.twig';
        }

        return match ($cell->getType()) {
            'ROOM' => 'map/_room.html.twig',
            'CORRIDOR' => 'map/_corridor.html.twig',
            'WALL' => 'map/_wall.html.twig'
        };
    }
}
