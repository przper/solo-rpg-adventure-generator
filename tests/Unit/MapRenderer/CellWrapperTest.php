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
}
