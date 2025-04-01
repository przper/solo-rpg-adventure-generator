<?php

namespace App\Tests\Unit\Core;

use App\Core\Helper\Coordinates;
use App\Core\Map\Corridor;
use App\Core\Map\Tile;
use App\Core\Map\TileType;
use PHPUnit\Framework\TestCase;

class CorridorTest extends TestCase
{
    public function testCreateWithValidCoordinates(): void
    {
        $coordinates = [
            Coordinates::fromIntegers(1, 1),
            Coordinates::fromIntegers(1, 2),
            Coordinates::fromIntegers(1, 3),
        ];

        $corridor = Corridor::create($coordinates);

        $this->assertCount(3, $corridor->tiles);
        foreach ($corridor->tiles as $index => $tile) {
            $this->assertInstanceOf(Tile::class, $tile);
            $this->assertEquals($coordinates[$index], $tile->coordinates);
            $this->assertEquals(TileType::Corridor, $tile->type);
        }
    }

    public function testCreateWithDuplicateCoordinatesThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Duplicate coordinates found in the provided tiles.');

        $coordinates = [
            Coordinates::fromIntegers(2, 1),
            Coordinates::fromIntegers(2, 1), // Duplicate coordinate
        ];

        Corridor::create($coordinates);
    }
}
