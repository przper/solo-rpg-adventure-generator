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

    public function render(MapInterface $map, ?Game $game = null): MapRender
    {
        /** @var CellWrapper[][] */
        $cells = [];

        foreach ($map->getCells() as $column) {
            $cells[] = array_map(function (MapCellInterface $baseCell) use ($game) {
                $cellWrapper = new CellWrapper($baseCell);

                if (! is_null($game)) {
                    $cellWrapper->applyGameState($game);
                }

                return $cellWrapper;
            }, $column);
        }

        $html = $this->twig->render('map/map.html.twig', [
            'cells' => $cells,
        ]);
        
        return new MapRender($cells, $html);
    }
}
