<?php

namespace App\MapRendering;

final readonly class MapRender
{
    /**
     * @param CellWrapper[][] $cells
     * @param string $html
     *
     * @return void
     */
    public function __construct(
        public array $cells,
        public string $html
    ) {
        //
    }
}
