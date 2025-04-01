<?php

namespace App\MapRendering;

use App\Core\Helper\Coordinates;
use App\Core\Map\Map;
use App\Core\Map\Tile;
use App\Core\Map\TileType;
use App\Game\Game;
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
