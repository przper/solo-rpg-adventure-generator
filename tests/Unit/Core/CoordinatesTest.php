<?php

namespace App\Tests\Unit\Core;

use App\Core\Helper\Coordinates;
use PHPUnit\Framework\TestCase;

class CoordinatesTest extends TestCase
{
    /** @test */
    public function it_can_be_created_from_pair_of_integers()
    {
        $coordinates = Coordinates::fromIntegers(0, 1);

        $this->assertEquals(0, $coordinates->x);
        $this->assertEquals(1, $coordinates->y);
    }

    /** @test */
    public function it_can_be_moved()
    {
        $coordinates = Coordinates::fromIntegers(10, 11);
        $coordinates = $coordinates->moveBy(-1, -1);

        $this->assertEquals(9, $coordinates->x);
        $this->assertEquals(10, $coordinates->y);
    }

    /** @test */
    public function it_is_json_serializable()
    {
        $coordinates = Coordinates::fromIntegers(0, 1);

        $this->assertJson($coordinates);
        $this->assertJsonStringEqualsJsonString(
            json_encode(['x' => 0,'y' => 1]),
            json_encode($coordinates)
        );
    }

    /** @test */
    public function it_can_be_printed()
    {
        $coordinates = Coordinates::fromIntegers(0, 1);

        $this->assertEquals("[0, 1]", $coordinates);
    }

    /**
     * @dataProvider coordinatesProvider
     */
    public function test_is_same_coordinates(int $x1, int $y1, int $x2, int $y2, bool $expected): void
    {
        $coordinates1 = Coordinates::fromIntegers($x1, $y1);
        $coordinates2 = Coordinates::fromIntegers($x2, $y2);

        $this->assertEquals($expected, $coordinates1->isSame($coordinates2));
    }

    public function coordinatesProvider(): \Generator
    {
        // Happy path scenario
        yield [0, 0, 0, 0, true];

        // Edge cases
        yield [PHP_INT_MAX, PHP_INT_MAX, PHP_INT_MAX, PHP_INT_MAX, true];
        yield [PHP_INT_MIN, PHP_INT_MIN, PHP_INT_MIN, PHP_INT_MIN, true];

        // Invalid scenarios
        yield [0, 0, 0, 1, false];
        yield [0, 0, 1, 0, false];
        yield [1, 1, 2, 2, false];
    }

    /**
     * @dataProvider distanceProvider
     */
    public function test_get_distance_to_coordinates(int $x1, int $y1, int $x2, int $y2, float $expectedDistance): void
    {
        $coordinates1 = Coordinates::fromIntegers($x1, $y1);
        $coordinates2 = Coordinates::fromIntegers($x2, $y2);

        $this->assertEquals($expectedDistance, $coordinates1->getDistanceTo($coordinates2));
    }

    public function distanceProvider(): \Generator
    {
        yield [0, 0, 0, 0, 0.0];
        yield [0, 0, 3, 4, 5.0];
        yield [-1, -1, 1, 1, 2.828];
    }
}
