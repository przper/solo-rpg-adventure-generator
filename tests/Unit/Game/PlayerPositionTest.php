<?php

namespace App\Tests\Unit\Game;

use PHPUnit\Framework\TestCase;
use App\Service\Game\PlayerPosition;
use App\Tests\Unit\Game\Fixtures\DummyMapCell;

class PlayerPositionTest extends TestCase
{
    /** @test */
    public function it_has_x_and_y_coordinates()
    {
        $position = new PlayerPosition(0, 0);

        $this->assertEquals(0, $position->getX());
        $this->assertEquals(0, $position->getY());
    }

    /** @test */
    public function it_can_be_moved()
    {
        $position = new PlayerPosition(10, 10);
        $position->move(1, 1);

        $this->assertEquals(11, $position->getX());
        $this->assertEquals(11, $position->getY());
    }

    /** @test */
    public function it_can_be_created_from_MapCell()
    {
        $cell = new DummyMapCell();
        $position = PlayerPosition::fromCell($cell);

        $this->assertEquals(0, $position->getX());
        $this->assertEquals(0, $position->getY());
    }
}
