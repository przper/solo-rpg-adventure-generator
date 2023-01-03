<?php

namespace App\Tests\Unit\Helper;

use App\Helper\Coordinates;
use PHPUnit\Framework\TestCase;

class CoordinatesTest extends TestCase
{
    /** @test */
    public function it_has_x_and_y()
    {
        $coordinates = new Coordinates();

        $coordinates->setX(0);
        $coordinates->setY(1);

        $this->assertEquals(0, $coordinates->getX());
        $this->assertEquals(1, $coordinates->getY());
    }

    /** @test */
    public function x_and_y_setter()
    {
        $coordinates = new Coordinates();

        $coordinates->setXY(0, 1);

        $this->assertEquals(0, $coordinates->getX());
        $this->assertEquals(1, $coordinates->getY());
    }

    /** @test */
    public function it_can_be_moved()
    {
        $coordinates = new Coordinates();

        $coordinates->setXY(10, 11);
        $coordinates->moveBy(-1, -1);
        
        $this->assertEquals(9, $coordinates->getX());
        $this->assertEquals(10, $coordinates->getY());
    }

    /** @test */
    public function it_can_be_created_from_pair_of_integers()
    {
        $coordinates = Coordinates::fromIntegers(0, 1);

        $this->assertEquals(0, $coordinates->getX());
        $this->assertEquals(1, $coordinates->getY());
    }

    /** @test */
    public function it_is_json_serializable()
    {
        $coordinates = new Coordinates();

        $coordinates->setX(0);
        $coordinates->setY(1);

        $this->assertJson($coordinates);
        $this->assertJsonStringEqualsJsonString(
            json_encode(['x' => 0,'y' => 1]),
            json_encode($coordinates)
        );
    }

    /** @test */
    public function it_can_be_printed()
    {
        $coordinates = new Coordinates();

        $coordinates->setX(0);
        $coordinates->setY(1);
        
        $this->assertEquals("[0, 1]", $coordinates);
    }
}