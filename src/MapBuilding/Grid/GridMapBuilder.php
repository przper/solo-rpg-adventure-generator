<?php

namespace App\MapBuilding\Grid;

use App\Core\Helper\Coordinates;
use App\Core\Map\Corridor;
use App\Core\Map\Map;
use App\Core\Map\Room;
use App\Core\Map\TileType;
use App\MapBuilding\MapBuilderInterface;

final class GridMapBuilder implements MapBuilderInterface
{
    // Map configuration constants
    private const DEFAULT_GRID_WIDTH = 5;
    private const DEFAULT_GRID_HEIGHT = 4;
    private const DEFAULT_ROOM_SIZE = 3;
    private const DEFAULT_CORRIDOR_LENGTH = 4;

    // Map elements
    private const ROOM = 'ROOM';
    private const CORRIDOR = 'CORRIDOR';

    private int $gridWidth = self::DEFAULT_GRID_WIDTH;
    private int $gridHeight = self::DEFAULT_GRID_HEIGHT;
    private int $roomSize = self::DEFAULT_ROOM_SIZE;
    private int $corridorLength = self::DEFAULT_CORRIDOR_LENGTH;

    public function setGridWidth(int $gridWidth): self
    {
        $this->gridWidth = $gridWidth;
        return $this;
    }

    public function setGridHeight(int $gridHeight): self
    {
        $this->gridHeight = $gridHeight;
        return $this;
    }

    public function setRoomSize(int $roomSize): self
    {
        $this->roomSize = $roomSize;
        return $this;
    }

    public function setCorridorLength(int $corridorLength): self
    {
        $this->corridorLength = $corridorLength;
        return $this;
    }

    public function build(): Map
    {
        $maxAttempts = 10;

        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            // Generate grid map structure
            $gridMap = $this->generateGridMap();

            // Get dimensions from the grid map
            $mapHeight = count($gridMap);
            $mapWidth = $mapHeight > 0 ? count($gridMap[0]) : 0;

            // Generate tiles for the map
            $mapElements = $this->buildMapElementsFromGrid($gridMap);

            // Create immutable map with all tiles
            $map = new Map($mapWidth, $mapHeight, $mapElements);

            // Check if all rooms are accessible from the starting room
            if ($this->allRoomsAreAccessible($map)) {
                return $map;
            }
        }

        // If we've made too many attempts without success, throw an exception
        throw new \RuntimeException(
            sprintf(
                'Failed to generate a connected map after %d attempts with configuration: grid %dx%d, room size %d, corridor length %d',
                $maxAttempts,
                $this->gridWidth,
                $this->gridHeight,
                $this->roomSize,
                $this->corridorLength
            )
        );
    }

    private function generateGridMap(): array
    {
        // Calculate dimensions based on grid configuration
        $mapWidth = $this->gridWidth * $this->roomSize + ($this->gridWidth - 1) * $this->corridorLength;
        $mapHeight = $this->gridHeight * ($this->roomSize) + ($this->gridHeight - 1)* $this->corridorLength;

        // Initialize empty map
        $gridMap = array_fill(0, $mapHeight, array_fill(0, $mapWidth, null));

        // Room positions tracking
        $rooms = [];

        // Generate rooms
        for ($gridY = 0; $gridY < $this->gridHeight; $gridY++) {
            for ($gridX = 0; $gridX < $this->gridWidth; $gridX++) {
                // Calculate top-left corner of the room in the grid
                $roomX = $gridX * ($this->roomSize + $this->corridorLength);
                $roomY = $gridY * ($this->roomSize + $this->corridorLength);

                // Create room
                for ($y = 0; $y < $this->roomSize; $y++) {
                    for ($x = 0; $x < $this->roomSize; $x++) {
                        $gridMap[$roomY + $y][$roomX + $x] = self::ROOM;
                    }
                }

                // Store room information for connecting later
                $rooms[] = [
                    'gridX' => $gridX,
                    'gridY' => $gridY,
                    'x' => $roomX,
                    'y' => $roomY,
                    'connected' => false
                ];
            }
        }

        // Connect rooms with corridors
        $this->connectRooms($gridMap, $rooms);
        $this->ensureAllRoomsConnected($gridMap, $rooms);

        return $gridMap;
    }

    private function connectRooms(array &$gridMap, array &$rooms): void
    {
        $roomCount = count($rooms);

        // Create a random number of corridors (at least enough to potentially connect all rooms)
        $minCorridors = $roomCount - 1;
        $maxCorridors = $roomCount * 2;
        $corridorCount = rand($minCorridors, $maxCorridors);

        for ($i = 0; $i < $corridorCount; $i++) {
            // Select two random rooms
            $roomIndex1 = rand(0, $roomCount - 1);
            $roomIndex2 = rand(0, $roomCount - 1);

            // Make sure we're not connecting a room to itself
            if ($roomIndex1 == $roomIndex2) {
                continue;
            }

            $room1 = &$rooms[$roomIndex1];
            $room2 = &$rooms[$roomIndex2];

            // Only connect if they're adjacent in the grid
            $xDiff = abs($room1['gridX'] - $room2['gridX']);
            $yDiff = abs($room1['gridY'] - $room2['gridY']);

            // Rooms must be adjacent in only one direction
            if (($xDiff == 1 && $yDiff == 0) || ($xDiff == 0 && $yDiff == 1)) {
                $this->createCorridor($gridMap, $room1, $room2);
                $room1['connected'] = true;
                $room2['connected'] = true;
            }
        }
    }

    private function createCorridor(array &$gridMap, array $room1, array $room2): void
    {
        // Determine direction of corridor
        $horizontal = $room1['gridY'] == $room2['gridY'];

        if ($horizontal) {
            // Horizontal corridor
            $startRoom = ($room1['gridX'] < $room2['gridX']) ? $room1 : $room2;
            $endRoom = ($room1['gridX'] < $room2['gridX']) ? $room2 : $room1;
            $startX = $startRoom['x'] + $this->roomSize;
            $endX = $endRoom['x']; // Stop at the beginning of the next room
            $y = $startRoom['y'] + floor($this->roomSize / 2);

            for ($x = $startX; $x < $endX; $x++) {
                // Only place corridor if the cell is empty
                if ($gridMap[$y][$x] === null) {
                    $gridMap[$y][$x] = self::CORRIDOR;
                }
            }
        } else {
            // Vertical corridor
            $startRoom = ($room1['gridY'] < $room2['gridY']) ? $room1 : $room2;
            $endRoom = ($room1['gridY'] < $room2['gridY']) ? $room2 : $room1;
            $x = $startRoom['x'] + floor($this->roomSize / 2);
            $startY = $startRoom['y'] + $this->roomSize;
            $endY = $endRoom['y']; // Stop at the beginning of the next room

            for ($y = $startY; $y < $endY; $y++) {
                // Only place corridor if the cell is empty
                if ($gridMap[$y][$x] === null) {
                    $gridMap[$y][$x] = self::CORRIDOR;
                }
            }
        }
    }

    private function ensureAllRoomsConnected(array &$gridMap, array &$rooms): void
    {
        $connectedRooms = [];
        $unconnectedRooms = [];

        // Separate connected and unconnected rooms
        foreach ($rooms as $index => $room) {
            if ($room['connected']) {
                $connectedRooms[] = $index;
            } else {
                $unconnectedRooms[] = $index;
            }
        }

        // Connect each unconnected room to a random connected room
        foreach ($unconnectedRooms as $unconnectedIndex) {
            if (empty($connectedRooms)) {
                // If no connected rooms yet, connect to the first unconnected room
                if (count($unconnectedRooms) > 1) {
                    $connectedIndex = $unconnectedRooms[0];
                    $this->forceConnect($gridMap, $rooms, $unconnectedIndex, $connectedIndex);
                    $connectedRooms[] = $unconnectedIndex;
                    $connectedRooms[] = $connectedIndex;
                }
            } else {
                // Connect to a random connected room
                $connectedIndex = $connectedRooms[array_rand($connectedRooms)];
                $this->forceConnect($gridMap, $rooms, $unconnectedIndex, $connectedIndex);
                $connectedRooms[] = $unconnectedIndex;
            }
        }
    }

    private function forceConnect(array &$gridMap, array &$rooms, int $roomIndex1, int $roomIndex2): void
    {
        $room1 = &$rooms[$roomIndex1];
        $room2 = &$rooms[$roomIndex2];

        // Find the shortest path
        if ($room1['gridX'] == $room2['gridX']) {
            // Same column - connect vertically
            $this->createVerticalConnection($gridMap, $room1, $room2);
        } else if ($room1['gridY'] == $room2['gridY']) {
            // Same row - connect horizontally
            $this->createHorizontalConnection($gridMap, $room1, $room2);
        } else {
            // Need to create an L-shaped path
            // First, choose a random intermediate room
            $intermediateRoom = null;

            // Try to find an available intermediate room
            $potentialRooms = array_filter($rooms, function($room) use ($room1, $room2) {
                return ($room['gridX'] == $room1['gridX'] && $room['gridY'] == $room2['gridY']) ||
                    ($room['gridX'] == $room2['gridX'] && $room['gridY'] == $room1['gridY']);
            });

            if (!empty($potentialRooms)) {
                $intermediateRoom = reset($potentialRooms);

                // Connect room1 to intermediate room
                if ($room1['gridX'] == $intermediateRoom['gridX']) {
                    $this->createVerticalConnection($gridMap, $room1, $intermediateRoom);
                } else {
                    $this->createHorizontalConnection($gridMap, $room1, $intermediateRoom);
                }

                // Connect intermediate room to room2
                if ($intermediateRoom['gridX'] == $room2['gridX']) {
                    $this->createVerticalConnection($gridMap, $intermediateRoom, $room2);
                } else {
                    $this->createHorizontalConnection($gridMap, $intermediateRoom, $room2);
                }
            }
        }

        $room1['connected'] = true;
        $room2['connected'] = true;
    }

    private function createHorizontalConnection(array &$gridMap, array $room1, array $room2): void
    {
        // Ensure room1 is to the left of room2
        if ($room1['gridX'] > $room2['gridX']) {
            $temp = $room1;
            $room1 = $room2;
            $room2 = $temp;
        }

        $startX = $room1['x'] + $this->roomSize;
        $endX = $room2['x'];
        $y = $room1['y'] + floor($this->roomSize / 2);

        for ($x = $startX; $x < $endX; $x++) {
            // Only place corridor if the cell is empty
            if ($gridMap[$y][$x] === null) {
                $gridMap[$y][$x] = self::CORRIDOR;
            }
        }
    }

    private function createVerticalConnection(array &$gridMap, array $room1, array $room2): void
    {
        // Ensure room1 is above room2
        if ($room1['gridY'] > $room2['gridY']) {
            $temp = $room1;
            $room1 = $room2;
            $room2 = $temp;
        }

        $startY = $room1['y'] + $this->roomSize;
        $endY = $room2['y'];
        $x = $room1['x'] + floor($this->roomSize / 2);

        for ($y = $startY; $y < $endY; $y++) {
            // Only place corridor if the cell is empty
            if ($gridMap[$y][$x] === null) {
                $gridMap[$y][$x] = self::CORRIDOR;
            }
        }
    }

    /**
     * @param array $gridMap
     * @return array<Room|Corridor>
     */
    private function buildMapElementsFromGrid(array $gridMap): array
    {
        $mapHeight = count($gridMap);
        $mapWidth = $mapHeight > 0 ? count($gridMap[0]) : 0;
        $elements = [];

        // First pass: identify all room and corridor cells
        $roomCoords = [];
        $corridorCoords = [];

        for ($y = 0; $y < $mapHeight; $y++) {
            for ($x = 0; $x < $mapWidth; $x++) {
                $cellType = $gridMap[$y][$x];
                if ($cellType === null) continue;

                $coordinates = Coordinates::fromIntegers($x, $y);

                if ($cellType === self::ROOM) {
                    $roomCoords[] = $coordinates;
                } elseif ($cellType === self::CORRIDOR) {
                    $corridorCoords[] = $coordinates;
                }
            }
        }

        // Second pass: group connected rooms and corridors
        $roomGroups = $this->groupConnectedTiles($roomCoords);
        $corridorGroups = $this->groupConnectedTiles($corridorCoords);

        // Create Room objects from the grouped coordinates
        foreach ($roomGroups as $group) {
            // Check if this is the starter room (contains 0,0)
            $isStarterRoom = false;
            foreach ($group as $coords) {
                if ($coords->x === 0 && $coords->y === 0) {
                    $isStarterRoom = true;
                    break;
                }
            }

            if ($isStarterRoom) {
                // Empty starter room - no enemies or treasure
                $elements[] = Room::create($group);
            } else {
                // Generate a room using the factory method
                $elements[] = Room::create($group);
            }
        }

        // Create Corridor objects from the grouped coordinates
        foreach ($corridorGroups as $group) {
            // Generate a corridor using the factory method
            $elements[] = Corridor::create($group);
        }

        return $elements;
    }

    /**
     * Groups connected coordinates using a breadth-first search approach
     *
     * @param Coordinates[] $coordinates List of coordinates to group
     * @return array Array of coordinate groups
     */
    private function groupConnectedTiles(array $coordinates): array
    {
        // If no coordinates, return empty array
        if (empty($coordinates)) {
            return [];
        }

        // Map coordinates to strings for easier lookup
        $coordStrings = [];
        foreach ($coordinates as $coord) {
            $coordStrings[(string)$coord] = $coord;
        }

        $groups = [];
        $visited = [];

        // Process each coordinate
        foreach ($coordStrings as $coordStr => $coord) {
            // Skip if already assigned to a group
            if (isset($visited[$coordStr])) {
                continue;
            }

            // Start a new group with this coordinate
            $group = [];
            $queue = new \SplQueue();
            $queue->enqueue($coord);
            $visited[$coordStr] = true;

            // BFS to find all connected tiles
            while (!$queue->isEmpty()) {
                $current = $queue->dequeue();
                $group[] = $current;

                // Check all four directions for connected tiles
                $neighbors = [
                    Coordinates::fromIntegers($current->x + 1, $current->y),
                    Coordinates::fromIntegers($current->x - 1, $current->y),
                    Coordinates::fromIntegers($current->x, $current->y + 1),
                    Coordinates::fromIntegers($current->x, $current->y - 1)
                ];

                foreach ($neighbors as $neighbor) {
                    $neighborStr = (string)$neighbor;

                    // If neighbor exists in our list and hasn't been visited
                    if (isset($coordStrings[$neighborStr]) && !isset($visited[$neighborStr])) {
                        $queue->enqueue($coordStrings[$neighborStr]);
                        $visited[$neighborStr] = true;
                    }
                }
            }

            // Add this group to our results
            $groups[] = $group;
        }

        return $groups;
    }


    /**
     * Checks if all rooms in the map are accessible from the starting room at (0,0)
     */
    private function allRoomsAreAccessible(Map $map): bool
    {
        // Get all room tiles
        $roomTiles = $map->getTilesByType(TileType::Room);
        if (empty($roomTiles)) {
            return false;
        }

        // Get the starting coordinates
        $startCoordinates = Coordinates::fromIntegers(0, 0);
        $startTile = $map->getTile($startCoordinates);

        // Make sure the starting tile is a room
        if ($startTile === null || $startTile->type !== TileType::Room) {
            return false;
        }

        // Find all reachable tiles using breadth-first search
        $reachableTiles = $this->findReachableTiles($map, $startCoordinates);

        // Check if all rooms are reachable
        foreach ($roomTiles as $tile) {
            $coords = $tile->coordinates;
            $key = $coords->x . ',' . $coords->y;

            if (!in_array($key, $reachableTiles)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Uses breadth-first search to find all tiles reachable from the given starting coordinates
     *
     * @param Map $map
     * @param Coordinates $start
     * @return array List of reachable coordinates in "x,y" format
     */
    private function findReachableTiles(Map $map, Coordinates $start): array
    {
        $queue = new \SplQueue();
        $queue->enqueue($start);

        $visited = [
            $start->x . ',' . $start->y => true
        ];

        while (!$queue->isEmpty()) {
            /** @var Coordinates $current */
            $current = $queue->dequeue();
            $currentTile = $map->getTile($current);

            // Skip if the tile doesn't exist or is a wall
            if ($currentTile === null || $currentTile->type === TileType::Wall) {
                continue;
            }

            // Get neighbors (adjacent tiles)
            $nearbyTiles = $map->getNearbyTiles($current);

            foreach ($nearbyTiles as $tile) {
                $coords = $tile->coordinates;
                $key = $coords->x . ',' . $coords->y;

                // If we haven't visited this tile yet
                if (!isset($visited[$key])) {
                    $visited[$key] = true;
                    $queue->enqueue($coords);
                }
            }
        }

        return array_keys($visited);
    }
}
