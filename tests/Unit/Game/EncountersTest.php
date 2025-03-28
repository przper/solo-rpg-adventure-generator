<?php

namespace App\Tests\Unit\Game;

use App\Enum\EncounterDifficulty;
use App\Helper\Coordinates;
use App\Helper\DiceStack;
use App\Service\EncountersPlanner\Encounter;
use App\Service\EncountersPlanner\EncountersPlan;
use App\Service\EncountersPlanner\Enemy;
use App\Service\Game\Encounters;
use App\Service\Map\Core\Corridor;
use App\Service\Map\Core\Map;
use App\Service\Map\Core\Room;
use PHPUnit\Framework\TestCase;

class EncountersTest extends TestCase
{
    /**
     * Happy Path
     */
    public function test_it_places_encounters_based_on_map(): void
    {
        /**
         * Map visual representation:
         *
         * R R # # #
         * R R C R R
         * # # # R R
         * # # # C #
         * # R R R #
         */
        $map = new Map(5, 5, [
            Room::create([
                Coordinates::fromIntegers(0, 0),
                Coordinates::fromIntegers(1, 0),
                Coordinates::fromIntegers(0, 1),
                Coordinates::fromIntegers(1, 1),
            ]),
            Corridor::create([
                Coordinates::fromIntegers(2, 1),
            ]),
            Room::create([
                Coordinates::fromIntegers(3, 1),
                Coordinates::fromIntegers(4, 1),
                Coordinates::fromIntegers(3, 2),
                Coordinates::fromIntegers(4, 2),
            ]),
            Corridor::create([
                Coordinates::fromIntegers(3, 3),
            ]),
            Room::create([
                Coordinates::fromIntegers(1, 4),
                Coordinates::fromIntegers(2, 4),
                Coordinates::fromIntegers(3, 4),
            ]),
        ]);

        $plan = new EncountersPlan([
            new Encounter(EncounterDifficulty::EASY, [
                new Enemy(1, 10, 'MiniBebok', DiceStack::fromString('1d6'), 11, DiceStack::fromString('1d4')),
            ]),
            new Encounter(EncounterDifficulty::MEDIUM, [
                new Enemy(5, 50, 'Bebok', DiceStack::fromString('4d6'), 13, DiceStack::fromString('1d8')),
            ]),
            new Encounter(EncounterDifficulty::DEADLY, [
                new Enemy(10, 500, 'MegaBebok', DiceStack::fromString('24d8'), 16, DiceStack::fromString('2d8')),
            ]),
        ]);

        $sut = new Encounters($map, $plan);

        $this->assertNull($sut->getEncounter(Coordinates::fromIntegers(0, 0)));
    }

    /** @group EncountersPlacement */
    public function test_if_there_is_more_map_elements_than_encounters(): void
    {
        $map = new Map(5, 5, [
            Room::create([Coordinates::fromIntegers(0, 0)]),
            Corridor::create([Coordinates::fromIntegers(1, 0)]),
            Room::create([Coordinates::fromIntegers(2, 0)]),
        ]);
        $plan = new EncountersPlan([
            new Encounter(EncounterDifficulty::DEADLY, []),
            new Encounter(EncounterDifficulty::DEADLY, []),
            new Encounter(EncounterDifficulty::DEADLY, []),
        ]);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Map Elements count must be greater than Encounters count');

        new Encounters($map, $plan);
    }

    /** @group EncountersPlacement */
    public function test_non_easy_encounters_should_be_placed_in_rooms(): void
    {
        $map = new Map(5, 5, [
            Room::create([Coordinates::fromIntegers(0, 0)]),
            Corridor::create([Coordinates::fromIntegers(1, 0)]),
            Room::create([Coordinates::fromIntegers(2, 0)]),
        ]);
        $encounter = new Encounter(EncounterDifficulty::DEADLY, []);
        $sut = new Encounters($map, new EncountersPlan([$encounter]));

        $this->assertNull($sut->getEncounter(Coordinates::fromIntegers(0, 0)));
        $this->assertSame($encounter, $sut->getEncounter(Coordinates::fromIntegers(2, 0)));
        $this->assertNull($sut->getEncounter(Coordinates::fromIntegers(1, 0)));
    }

    /** @group EncountersPlacement */
    public function test_easy_encounters_should_be_placed_in_corridors_if_possible(): void
    {
        $map = new Map(1, 1, [
            Room::create([Coordinates::fromIntegers(0, 0)]),
            Corridor::create([Coordinates::fromIntegers(1, 0)]),
            Room::create([Coordinates::fromIntegers(2, 0)]),
        ]);

        $encounter = new Encounter(EncounterDifficulty::EASY, []);
        $plan = new EncountersPlan([$encounter]);

        $sut = new Encounters($map, $plan);

        $this->assertNull($sut->getEncounter(Coordinates::fromIntegers(0, 0)));
        $this->assertSame($encounter, $sut->getEncounter(Coordinates::fromIntegers(1, 0)));
        $this->assertNull($sut->getEncounter(Coordinates::fromIntegers(2, 0)));
    }

    /** @group EncountersPlacement */
    public function test_easy_encounters_should_be_placed_in_rooms_if_no_corridors_available(): void
    {
        $map = new Map(2, 2, [
            Room::create([
                Coordinates::fromIntegers(0, 0),
            ]),
            Room::create([
                Coordinates::fromIntegers(1, 0),
            ]),
        ]);

        $encounter = new Encounter(EncounterDifficulty::EASY, []);
        $plan = new EncountersPlan([$encounter]);

        $sut = new Encounters($map, $plan);

        $hasEncounter = false;
        for ($x = 0; $x <= 1; $x++) {
            for ($y = 0; $y <= 1; $y++) {
                $coords = Coordinates::fromIntegers($x, $y);
                if ($sut->getEncounter($coords) === $encounter) {
                    $hasEncounter = true;
                    break 2;
                }
            }
        }

        $this->assertNull($sut->getEncounter(Coordinates::fromIntegers(0, 0)));
        $this->assertTrue($hasEncounter, 'EASY encounter should be placed in rooms if no corridors are available');
    }

    /** @group EncountersPlacement */
    public function test_throws_exception_if_no_elements_available_for_encounters(): void
    {
        $map = new Map(1, 1, [
            Room::create([Coordinates::fromIntegers(0, 0)]),
            Corridor::create([Coordinates::fromIntegers(1, 0)]),
            Room::create([Coordinates::fromIntegers(2, 0)]),
        ]);

        $plan = new EncountersPlan([
            new Encounter(EncounterDifficulty::DEADLY, []),
            new Encounter(EncounterDifficulty::DEADLY, []),
        ]);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('No available elements to place the encounter.');

        new Encounters($map, $plan);
    }
}
