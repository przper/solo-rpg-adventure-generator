<?php

namespace App\Tests\RailroadMapGenerator;

use App\Service\RailroadGenerator\Corridor;
use PHPUnit\Framework\TestCase;

class CorridorTest extends TestCase
{
    /** @test */
    public function it_has_y_coordinate()
    {
        $corridor = Corridor::fromY(5);

        $this->assertIsNumeric($corridor->getY());
        $this->assertEquals(5, $corridor->getY());
    }

    /** @test */
    public function it_has_type()
    {
        $corridor = Corridor::fromY(5);

        $this->assertIsString($corridor->getType());
        $this->assertEquals(Corridor::TYPE, $corridor->getType());
    }

    /** @test */
    public function it_has_template()
    {
        $corridor = Corridor::fromY(5);

        $this->assertIsString($corridor->getTemplate());
        $this->assertFileExists("templates/".$corridor->getTemplate());
    }
}
