<?php

namespace App\Tests\Unit\Map\Core;

use App\Enum\MovementType;
use App\Helper\Coordinates;
use App\Service\Map\Core\Map;
use App\Service\Map\Core\TileTypes;
use PHPUnit\Framework\TestCase;

class MapTest extends TestCase
{
    private Map $sut;

    protected function setUp(): void
    {
        /**
         * Plain text example:
         *
         * R # # # #
         * C # # # #
         * C C C # #
         * # # R # #
         */
        $this->sut = new Map(
            width: 5,
            height: 4,
            tiles: [
                new DummyRoom(Coordinates::fromIntegers(0, 0)),
                new DummyCorridor(Coordinates::fromIntegers(0, 1)),
                new DummyCorridor(Coordinates::fromIntegers(0, 2)),
                new DummyCorridor(Coordinates::fromIntegers(1, 2)),
                new DummyCorridor(Coordinates::fromIntegers(2, 2)),
                new DummyRoom(Coordinates::fromIntegers(2, 3)),
            ],
        );
    }

    public function test_it_has_correct_dimensions(): void
    {
        $this->assertEquals(5, $this->sut->width);
        $this->assertEquals(4, $this->sut->height);
        $this->assertCount(4, $this->sut->tiles);
        $this->assertCount(5, $this->sut->tiles[0]);
        $this->assertCount(5, $this->sut->tiles[1]);
        $this->assertCount(5, $this->sut->tiles[2]);
        $this->assertCount(5, $this->sut->tiles[3]);
    }

    public function test_it_has_correctly_placed_cells(): void
    {
        $this->assertSame(
             "R####\n"
            ."C####\n"
            ."CCC##\n"
            ."##R##\n",
            $this->debugMap($this->sut),
        );
    }

    public function test_get_Tile_by_coordinates(): void
    {
        $this->assertInstanceOf(DummyRoom::class, $this->sut->getTile(Coordinates::fromIntegers(0, 0)));
        $this->assertInstanceOf(DummyCorridor::class, $this->sut->getTile(Coordinates::fromIntegers(0, 1)));
        $this->assertNull($this->sut->getTile(Coordinates::fromIntegers(1, 0)));
    }

    public function test_Map_has_correct_MovementType_assigned(): void
    {
        $this->assertEquals(MovementType::TwoDimension, $this->sut->movementType);

        $this->assertEquals(MovementType::OneDimension, (new Map(5, 1))->movementType);
        $this->assertEquals(MovementType::OneDimension, (new Map(1, 5))->movementType);
    }

    /** @dataProvider guardDimensions */
    public function test_Map_is_guarded_against_wrong_dimensions(int $width, int $height, bool $isValid): void
    {

        if ($isValid) {
            $map = new Map(width: $width, height: $height);
            $this->assertInstanceOf(Map::class, $map);
        } else {
            $this->expectException(\InvalidArgumentException::class);
            new Map(width: $width, height: $height);
        }
    }

    public function guardDimensions()
    {
        yield [1, 1, true];
        yield [100, 100, true];
        yield [100, 1, true];
        yield [1, 100, true];
        yield [0, 5, false];
        yield [5, 0, false];
        yield [0, 0, false];
        yield [-1, 5, false];
        yield [5, -1, false];
        yield [-1, -1, false];
    }

    private function debugMap(Map $map): string
    {
        $result = "";

        foreach ($map->tiles as $column) {
            foreach ($column as $tile) {
                $result .= is_null($tile) ? "#" : strtoupper(substr($tile->getType()->name, 0, 1));
            }
            $result .= "\n";
        }

        return $result;
    }

    public function test_it_filters_Tiles_by_TileType(): void
    {
        $this->assertCount(2, $this->sut->getTilesByType(TileTypes::Room));
        $this->assertCount(4, $this->sut->getTilesByType(TileTypes::Corridor));
        $this->assertCount(0, $this->sut->getTilesByType(TileTypes::Wall));
        $this->assertCount(6, $this->sut->getTilesByType(TileTypes::Room, TileTypes::Corridor));
    }
}
