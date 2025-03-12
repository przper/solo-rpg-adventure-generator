<?php

namespace App\Tests\Unit\Game;

use App\Service\Game\Movement;
use PHPUnit\Framework\TestCase;
use App\Enum\MovementDirection;

class MovementTest extends TestCase
{
    /**
     * @dataProvider providerTestAdd
     */
    public function test_add(MovementDirection $direction, int $value, int $expectedX, int $expectedY)
    {
        $movement = Movement::new();

        $movement = $movement->add($direction, $value);

        $this->assertEquals($expectedX, $movement->deltaX);
        $this->assertEquals($expectedY, $movement->deltaY);
    }

    public function providerTestAdd()
    {
        yield [MovementDirection::West, 2, -2, 0];
        yield [MovementDirection::East, 2, 2, 0];
        yield [MovementDirection::North, 2, 0, -2];
        yield [MovementDirection::South, 2, 0, 2];
    }

    public function test_new()
    {
        $movement = Movement::new();
        // The X and Y delta values of a new movement should be 0.
        $this->assertEquals(0, $movement->deltaX);
        $this->assertEquals(0, $movement->deltaY);
    }
}
