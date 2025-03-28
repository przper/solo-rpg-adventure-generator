<?php

namespace App\Service\Game;

use App\Enum\EncounterDifficulty;
use App\Helper\Coordinates;
use App\Service\EncountersPlanner\Encounter;
use App\Service\EncountersPlanner\EncountersPlan;
use App\Service\Map\Core\Corridor;
use App\Service\Map\Core\Map;
use App\Service\Map\Core\Room;

final class Encounters
{
    /** @var array<string, Encounter> */
    private array $encountersPerCoordinates = [];

    public function __construct(
        private Map $map,
        private EncountersPlan $encountersPlan,
    ) {
        $this->placeEncountersOnMap();
    }

    public function getEncountersPlan(): EncountersPlan
    {
        return $this->encountersPlan;
    }

    public function getEncounter(Coordinates $coordinates): ?Encounter
    {
        return $this->encountersPerCoordinates[(string) $coordinates] ?? null;
    }

    private function placeEncountersOnMap(): void
    {
        if (count($this->map->elements) - 1 < count($this->encountersPlan->encounters)) { // exclude starter room
            throw new \InvalidArgumentException("Map Elements count must be greater than Encounters count");
        }

        $emptyRooms = $this->map->getRooms();
        $emptyCorridors = $this->map->getCorridors();

        // Starter Room must always be empty
        $starterRoom = $this->map->getElementByCoordinates(Coordinates::fromIntegers(0, 0));
        $starterRoomKey = array_search($starterRoom, $emptyRooms);
        if ($starterRoomKey !== false) {
            unset($emptyRooms[$starterRoomKey]);
        }

        foreach ($this->encountersPlan->getEncountersSortedByDifficulty() as $encounter) {
            $this->placeEncounter($emptyRooms, $emptyCorridors, $encounter);
        }
    }

    /**
     * @param Room[] $emptyRooms
     * @param Corridor[] $emptyCorridors
     */
    private function placeEncounter(array &$emptyRooms, array &$emptyCorridors, Encounter $encounter): void
    {
//        switch ($encounter->getDifficulty()) {
//            case EncounterDifficulty::DEADLY:
//            case EncounterDifficulty::HARD:
//                $emptyElements = &$emptyRooms;
//                break;
//            case EncounterDifficulty::MEDIUM:
//                if ($emptyRooms === []) {
//                    $emptyElements = &$emptyCorridors;
//                    break;
//                }
//
//                if ($emptyCorridors === []) {
//                    $emptyElements = &$emptyRooms;
//                    break;
//                }
//
//                if (random_int(1, 100) < 67) {
//                    $emptyElements = &$emptyRooms;
//                } else {
//                    $emptyElements = &$emptyCorridors;
//                }
//                break;
//            case EncounterDifficulty::EASY:
//                $emptyElements = &$emptyCorridors;
//                break;
//        }

        if ($encounter->getDifficulty() === EncounterDifficulty::EASY) {
            if ($emptyCorridors === []) {
                $emptyElements = &$emptyRooms;
            } else {
                $emptyElements = &$emptyCorridors;
            }
        } else if ($encounter->getDifficulty() === EncounterDifficulty::MEDIUM) {
            if ($emptyCorridors === []) {
                $emptyElements = &$emptyRooms;
            } else {
                if (random_int(1, 100) < 33) {
                    $emptyElements = &$emptyCorridors;
                } else {
                    $emptyElements = &$emptyRooms;
                }
            }
        } else {
            $emptyElements = &$emptyRooms;
        }

        if ($emptyElements === []) {
            throw new \InvalidArgumentException("No available elements to place the encounter.");
        }

        $randomKey = array_rand($emptyElements);
        $randomElement = $emptyElements[$randomKey];

        $tiles = $randomElement->tiles;
        $tile = $tiles[array_rand($tiles)];

        $this->encountersPerCoordinates[(string) $tile->coordinates] = $encounter;
        unset($emptyElements[$randomKey]);
//        dump(
//            $encounter->getDifficulty()->name,
//            "Rooms: " . count($emptyRooms),
//            "Corridors: " . count($emptyCorridors),
//        );
    }
}
