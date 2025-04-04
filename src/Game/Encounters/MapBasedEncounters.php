<?php

namespace App\Game\Encounters;

use App\Core\Encounter\Encounter;
use App\Core\Encounter\EncounterDifficulty;
use App\Core\Helper\Coordinates;
use App\Core\Map\Corridor;
use App\Core\Map\Map;
use App\Core\Map\Room;
use App\EncountersPlanning\EncountersPlan;
use App\Game\EncountersInterface;
use InvalidArgumentException;
use Random\RandomException;

/**
 * Upon creation the Encounters from EncountersPlan are placed semi-randomly on Map.
 */
final class MapBasedEncounters implements EncountersInterface
{
    /** @var array<string, Encounter> */
    private array $encountersPerCoordinates = [];

    /**
     * @throws RandomException
     */
    public function __construct(
        private Map $map,
        private EncountersPlan $encountersPlan,
    ) {
        $this->placeEncountersOnMap();
    }

    public function getEncounter(Coordinates $coordinates): ?Encounter
    {
        return $this->encountersPerCoordinates[(string) $coordinates] ?? null;
    }

    public function resolve(Coordinates $coordinates, string $result): void
    {
        $encounter = $this->getEncounter($coordinates);

        if ($encounter) {
            $encounter->resolve($result);
        }
    }

    /**
     * @throws RandomException
     */
    private function placeEncountersOnMap(): void
    {
        if (
            $this->encountersPlan->encounters != []
            && count($this->map->elements) - 1 < count($this->encountersPlan->encounters)
        ) {
            throw new InvalidArgumentException("Map Elements count must be greater than Encounters count");
        }

        $emptyRooms = $this->map->getRooms();
        $emptyCorridors = $this->map->getCorridors();

        // Starter Room must always be empty
        $starterRoom = $this->map->getElementByCoordinates(Coordinates::fromIntegers(0, 0));
        $starterRoomKey = array_search($starterRoom, $emptyRooms);
        if ($starterRoomKey !== false) {
            unset($emptyRooms[$starterRoomKey]);
        }

         // Sort Rooms by distance to 0, 0 in ASC order
        usort($emptyRooms, function (Room $roomA, Room $roomB) {
            $coordinatesA = $roomA->tiles[0]->coordinates;
            $coordinatesB = $roomB->tiles[0]->coordinates;

            return $coordinatesA->getDistanceTo(Coordinates::fromIntegers(0, 0))
                <=> $coordinatesB->getDistanceTo(Coordinates::fromIntegers(0, 0));
        });

        foreach ($this->encountersPlan->getEncountersSortedByDifficulty() as $encounter) {
            $this->placeEncounter($emptyRooms, $emptyCorridors, $encounter);
        }
    }

    /**
     * @param Room[] $emptyRooms
     * @param Corridor[] $emptyCorridors
     * @throws RandomException|InvalidArgumentException
     */
    private function placeEncounter(array &$emptyRooms, array &$emptyCorridors, Encounter $encounter): void
    {
        if ($encounter->getDifficulty() === EncounterDifficulty::EASY) {
            if ($emptyCorridors === []) {
                $emptyElements = &$emptyRooms;
            } else {
                $emptyElements = &$emptyCorridors;
            }
        } else if ($encounter->getDifficulty() === EncounterDifficulty::MEDIUM) {
            if ($emptyCorridors === []) {
                $emptyElements = &$emptyRooms;
            } else if ($emptyRooms === []) {
                $emptyElements = &$emptyCorridors;
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
            throw new InvalidArgumentException("No available elements to place the encounter.");
        }

        if ($encounter->getDifficulty() === EncounterDifficulty::DEADLY) {
            $elementKey = array_key_last($emptyElements); // furthest Room
        } else {
            $elementKey = array_rand($emptyElements);
        }

        $element = $emptyElements[$elementKey];

        $tiles = $element->tiles;
        $tile = $tiles[array_rand($tiles)];

        $this->encountersPerCoordinates[(string) $tile->coordinates] = clone $encounter;
        unset($emptyElements[$elementKey]);
    }
}
