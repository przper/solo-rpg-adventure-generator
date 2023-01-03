<?php

namespace App\Service\MapRenderer;

final readonly class MapRender
{
    /**
     * @param CellWrapper[][] $cells
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