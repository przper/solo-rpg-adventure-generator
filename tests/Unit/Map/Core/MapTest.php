<?php

namespace App\Tests\Unit\Map\Core;

use App\Enum\MapDimension;
use App\Helper\Coordinates;
use App\Service\Map\Core\Corridor;
use App\Service\Map\Core\Map;
use App\Service\Map\Core\Room;
use App\Service\Map\Core\Tile;
use App\Service\Map\Core\TileType;
use App\Tests\DebugsMap;
use PHPUnit\Framework\TestCase;

class MapTest extends TestCase
{
    use DebugsMap;

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
            elements: [
                Room::create([Coordinates::fromIntegers(0, 0)]),
                Corridor::create([
                    Coordinates::fromIntegers(0, 1),
                    Coordinates::fromIntegers(0, 2),
                    Coordinates::fromIntegers(1, 2),
                    Coordinates::fromIntegers(2, 2),
                ]),
                Room::create([Coordinates::fromIntegers(2, 3)]),
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
        $tile1 =$this->sut->getTile(Coordinates::fromIntegers(0, 0));
        $this->assertInstanceOf(Tile::class, $tile1);
        $this->assertSame(TileType::Room, $tile1->type);

        $tile2 =$this->sut->getTile(Coordinates::fromIntegers(0, 1));
        $this->assertInstanceOf(Tile::class, $tile2);
        $this->assertSame(TileType::Corridor, $tile2->type);

        $this->assertNull($this->sut->getTile(Coordinates::fromIntegers(1, 0)));
    }

    public function test_Map_has_correct_MapDimension_assigned(): void
    {
        $this->assertEquals(MapDimension::TwoDimension, $this->sut->dimension);

        $this->assertEquals(MapDimension::OneDimension, (new Map(5, 1))->dimension);
        $this->assertEquals(MapDimension::OneDimension, (new Map(1, 5))->dimension);
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

    public function test_Map_is_guarded_against_overlapping_Coordinates_in_Elements(): void
    {
        $map = new Map(
            width: 5,
            height: 4,
            elements: [
                Room::create([Coordinates::fromIntegers(0, 0)]),
                Corridor::create([
                    Coordinates::fromIntegers(1, 0),
                    Coordinates::fromIntegers(1, 1),
                ]),
            ]
        );
        $this->assertInstanceOf(Map::class, $map);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Duplicate Coordinates of [0, 0]");
        new Map(
            width: 5,
            height: 4,
            elements: [
                Room::create([Coordinates::fromIntegers(0, 0)]),
                Corridor::create([
                    Coordinates::fromIntegers(0, 0),
                    Coordinates::fromIntegers(1, 0),
                ]),
            ]
        );
    }

    public function test_it_filters_Tiles_by_TileType(): void
    {
        $this->assertCount(2, $this->sut->getTilesByType(TileType::Room));
        $this->assertCount(4, $this->sut->getTilesByType(TileType::Corridor));
        $this->assertCount(0, $this->sut->getTilesByType(TileType::Wall));
        $this->assertCount(6, $this->sut->getTilesByType(TileType::Room, TileType::Corridor));
    }

    public function test_getNearbyTiles(): void
    {
        $nearbyTiles1 = $this->sut->getNearbyTiles(Coordinates::fromIntegers(1, 0));

        $this->assertCount(1, $nearbyTiles1);
        $this->assertEquals(TileType::Room, $nearbyTiles1[0]->type);
        $this->assertTrue($nearbyTiles1[0]->coordinates->isSame(Coordinates::fromIntegers(0, 0)));

        $nearbyTiles2 = $this->sut->getNearbyTiles(Coordinates::fromIntegers(2, 2));

        $this->assertCount(2, $nearbyTiles2);
        $this->assertEquals(TileType::Corridor, $nearbyTiles2[0]->type);
        $this->assertTrue($nearbyTiles2[0]->coordinates->isSame(Coordinates::fromIntegers(1, 2)));
        $this->assertEquals(TileType::Room, $nearbyTiles2[1]->type);
        $this->assertTrue($nearbyTiles2[1]->coordinates->isSame(Coordinates::fromIntegers(2, 3)));
    }

    public function test_getElementByCoordinates_handles_correct_and_empty_tiles(): void
    {
        $room = $this->sut->getElementByCoordinates(Coordinates::fromIntegers(0, 0));
        $this->assertInstanceOf(Room::class, $room);

        $corridor = $this->sut->getElementByCoordinates(Coordinates::fromIntegers(0, 1));
        $this->assertInstanceOf(Corridor::class, $corridor);

        $this->assertNull($this->sut->getElementByCoordinates(Coordinates::fromIntegers(1, 0)));
    }

    public function test_getRooms_returns_all_rooms(): void
    {
        $rooms = $this->sut->getRooms();

        $this->assertCount(2, $rooms);
        $this->assertInstanceOf(Room::class, $rooms[0]);
        $this->assertInstanceOf(Room::class, $rooms[1]);
    }

    public function test_getCorridors_returns_all_corridors(): void
    {
        $corridors = $this->sut->getCorridors();

        $this->assertCount(1, $corridors);
        $this->assertInstanceOf(Corridor::class, $corridors[0]);
    }
}
