<?php

namespace App\Tests\Integration\RailroadMapGenerator;

use App\Helper\Coordinates;
use App\Service\Map\Core\Corridor;
use App\Service\Map\Railroad\CorridorGenerator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CorridorGeneratorTest extends KernelTestCase
{
    private CorridorGenerator $generator;

    public function setUp(): void
    {
        $this->generator = static::getContainer()->get(CorridorGenerator::class);
    }

    /**
     * @test
     * @dataProvider corridorLength
     */
    public function it_generates_corridors(int $length, int $expectedTileCount)
    {
        $corridor = $this->generator->generate(Coordinates::fromIntegers(0, 0), $length);

        $this->assertInstanceOf(Corridor::class, $corridor);
        $this->assertCount($expectedTileCount, $corridor->tiles);

        $expectedCoordinates = array_map(
            fn($i) => (string) Coordinates::fromIntegers(0 + $i, 0),
            range(0, $length - 1)
        );

        $actualCoordinates = array_map(fn($tile) => (string) $tile->coordinates, $corridor->tiles);

        $this->assertEquals($expectedCoordinates, $actualCoordinates);
        $corridor = $this->generator->generate(Coordinates::fromIntegers(0, 0), 3);

        $this->assertInstanceOf(Corridor::class, $corridor);
        $this->assertCount(3, $corridor->tiles);
    }

    public function corridorLength()
    {
        yield [1, 1];
        yield [3, 3];
    }
}
