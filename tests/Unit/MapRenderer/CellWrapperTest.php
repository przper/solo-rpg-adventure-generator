<?php

namespace App\Tests\Unit\MapRenderer;

use PHPUnit\Framework\TestCase;
use App\Service\MapRenderer\CellWrapper;
use App\Tests\Unit\MapRenderer\Fixtures\DummyMapCell;

class CellWrapperTest extends TestCase
{
    /** @test */
    public function it_gets_attributes_from_baseCell()
    {
        $wrapper = new CellWrapper(new DummyMapCell());

        $this->assertEquals('DUMMY', $wrapper->type);

        $this->assertFalse($wrapper->getHasPlayer());
        $this->assertFalse($wrapper->getIsVisited());

        $this->assertEquals(0, $wrapper->x);
        $this->assertEquals(0, $wrapper->y);

        $this->assertNull($wrapper->treasure);
    }

    /** @test */
    public function it_resolves_templates()
    {
        $roomWrapper = new CellWrapper(new DummyMapCell('ROOM'));
        $roomWrapper->setIsVisited(true);

        $this->assertTrue($roomWrapper->getIsVisited());
        $this->assertEquals(CellWrapper::ROOM_TEMPLATE, $roomWrapper->getTemplate());

        $corridorWrapper = new CellWrapper(new DummyMapCell('CORRIDOR'));
        $corridorWrapper->setIsVisited(true);

        $this->assertTrue($corridorWrapper->getIsVisited());
        $this->assertEquals(CellWrapper::CORRIDOR_TEMPLATE, $corridorWrapper->getTemplate());
    }

    /** @test */
    public function not_visited_cells_are_hidden()
    {
        $roomWrapper = new CellWrapper(new DummyMapCell('ROOM'));

        $this->assertFalse($roomWrapper->getIsVisited());
        $this->assertEquals(CellWrapper::WALL_TEMPLATE, $roomWrapper->getTemplate());

        $corridorWrapper = new CellWrapper(new DummyMapCell('CORRIDOR'));

        $this->assertFalse($corridorWrapper->getIsVisited());
        $this->assertEquals(CellWrapper::WALL_TEMPLATE, $corridorWrapper->getTemplate());
    }
}
