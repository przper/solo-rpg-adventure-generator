<?php

namespace App\Tests\Unit\Helper;

use App\Helper\Coordinates;
use PHPUnit\Framework\TestCase;

class CoordinatesTest extends TestCase
{
    /** @test */
    public function it_can_be_created_from_pair_of_integers()
    {
        $coordinates = Coordinates::fromIntegers(0, 1);

        $this->assertEquals(0, $coordinates->getX());
        $this->assertEquals(1, $coordinates->getY());
    }

    /** @test */
    public function it_can_be_moved()
    {
        $coordinates = Coordinates::fromIntegers(10, 11);
        $coordinates = $coordinates->moveBy(-1, -1);

        $this->assertEquals(9, $coordinates->getX());
        $this->assertEquals(10, $coordinates->getY());
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
}
