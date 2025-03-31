<?php

namespace App\Service\MapRenderer;

use App\Helper\Coordinates;
use App\Service\Game\Game;
use App\Service\Map\Core\Map;
use App\Service\Map\Core\Tile;
use App\Service\Map\Core\TileType;
use Twig\Environment;

class MapRenderer
{
    public function __construct(
        private Environment $twig
    ) {
    }

    public function render(Map $map, ?Game $game = null): MapRender
    {
        /** @var CellWrapper[][] $cells */
        $cells = [];

        foreach ($map->tiles as $y => $row) {
            $cells[$y] = [];

            foreach ($row as $x => $tile) {
                $cell = $tile instanceof Tile
                    ? CellWrapper::fromTile($tile)
                    : new CellWrapper(TileType::Wall, Coordinates::fromIntegers($x, $y));

                if ($game instanceof Game) {
                    $cell->applyGameState($game);
                }

                $cells[$y][$x] = $cell;
            }
        }

        $html = $this->twig->render('map_html/index.html.twig', [
            'cells' => $cells,
        ]);

        return new MapRender($cells, $html);
    }
}
