<?php

namespace App\Tests\Unit\MapRendering;

use App\Core\Helper\Coordinates;
use App\Core\Map\Tile;
use App\Core\Map\TileType;
use App\MapRendering\CellWrapper;
use App\MapRendering\MapRender;
use PHPUnit\Framework\TestCase;

class MapRenderTest extends TestCase
{
    /** @test */
    public function it_has_cells()
    {
        $cell = CellWrapper::fromTile(new Tile(Coordinates::fromIntegers(0, 0), TileType::Room));
        $html = '<h1>test</h1>';

        $mapRender = new MapRender([[$cell]], $html);

        $this->assertInstanceOf(CellWrapper::class, $mapRender->cells[0][0]);
        $this->assertEquals($cell, $mapRender->cells[0][0]);
        $this->assertIsString($mapRender->html);
        $this->assertEquals($html, $mapRender->html);
    }
}
