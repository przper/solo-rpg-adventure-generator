<?php

class RoguelikeMapGenerator {
    // Map elements
    const ROOM = '#';
    const CORRIDOR = '*';
    const EMPTY_SPACE = ' ';

    // Grid dimensions
    const GRID_WIDTH = 3;
    const GRID_HEIGHT = 2;
    const ROOM_SIZE = 1;
    const CORRIDOR_LENGTH = 4;

    // Map dimensions
    private $mapWidth;
    private $mapHeight;
    private $map;

    // Room positions
    private $rooms = [];

    public function __construct() {
        // Calculate full map dimensions
        $this->mapWidth = self::GRID_WIDTH * (self::ROOM_SIZE + self::CORRIDOR_LENGTH);
        $this->mapHeight = self::GRID_HEIGHT * (self::ROOM_SIZE + self::CORRIDOR_LENGTH);

        // Initialize empty map
        $this->map = array_fill(0, $this->mapHeight, array_fill(0, $this->mapWidth, self::EMPTY_SPACE));

        // Generate the map
        $this->generateRooms();
        $this->connectRooms();
        $this->ensureAllRoomsConnected();
    }

    private function generateRooms() {
        for ($gridY = 0; $gridY < self::GRID_HEIGHT; $gridY++) {
            for ($gridX = 0; $gridX < self::GRID_WIDTH; $gridX++) {
                // Calculate top-left corner of the room in the grid
                $roomX = $gridX * (self::ROOM_SIZE + self::CORRIDOR_LENGTH);
                $roomY = $gridY * (self::ROOM_SIZE + self::CORRIDOR_LENGTH);

                // Create room
                for ($y = 0; $y < self::ROOM_SIZE; $y++) {
                    for ($x = 0; $x < self::ROOM_SIZE; $x++) {
                        $this->map[$roomY + $y][$roomX + $x] = self::ROOM;
                    }
                }

                // Store room information for connecting later
                $this->rooms[] = [
                    'gridX' => $gridX,
                    'gridY' => $gridY,
                    'x' => $roomX,
                    'y' => $roomY,
                    'connected' => false
                ];
            }
        }
    }

    private function connectRooms() {
        $roomCount = count($this->rooms);

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

            $room1 = &$this->rooms[$roomIndex1];
            $room2 = &$this->rooms[$roomIndex2];

            // Only connect if they're adjacent in the grid
            $xDiff = abs($room1['gridX'] - $room2['gridX']);
            $yDiff = abs($room1['gridY'] - $room2['gridY']);

            // Rooms must be adjacent in only one direction
            if (($xDiff == 1 && $yDiff == 0) || ($xDiff == 0 && $yDiff == 1)) {
                $this->createCorridor($room1, $room2);
                $room1['connected'] = true;
                $room2['connected'] = true;
            }
        }
    }

    private function createCorridor($room1, $room2) {
        // Determine direction of corridor
        $horizontal = $room1['gridY'] == $room2['gridY'];

        if ($horizontal) {
            // Horizontal corridor
            $startRoom = ($room1['gridX'] < $room2['gridX']) ? $room1 : $room2;
            $endRoom = ($room1['gridX'] < $room2['gridX']) ? $room2 : $room1;
            $startX = $startRoom['x'] + self::ROOM_SIZE;
            $endX = $endRoom['x']; // Stop at the beginning of the next room
            $y = $startRoom['y'] + floor(self::ROOM_SIZE / 2);

            for ($x = $startX; $x < $endX; $x++) {
                // Only place corridor if the cell is empty
                if ($this->map[$y][$x] === self::EMPTY_SPACE) {
                    $this->map[$y][$x] = self::CORRIDOR;
                }
            }
        } else {
            // Vertical corridor
            $startRoom = ($room1['gridY'] < $room2['gridY']) ? $room1 : $room2;
            $endRoom = ($room1['gridY'] < $room2['gridY']) ? $room2 : $room1;
            $x = $startRoom['x'] + floor(self::ROOM_SIZE / 2);
            $startY = $startRoom['y'] + self::ROOM_SIZE;
            $endY = $endRoom['y']; // Stop at the beginning of the next room

            for ($y = $startY; $y < $endY; $y++) {
                // Only place corridor if the cell is empty
                if ($this->map[$y][$x] === self::EMPTY_SPACE) {
                    $this->map[$y][$x] = self::CORRIDOR;
                }
            }
        }
    }

    private function ensureAllRoomsConnected() {
        $connectedRooms = [];
        $unconnectedRooms = [];

        // Separate connected and unconnected rooms
        foreach ($this->rooms as $index => $room) {
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
                    $this->forceConnect($unconnectedIndex, $connectedIndex);
                    $connectedRooms[] = $unconnectedIndex;
                    $connectedRooms[] = $connectedIndex;
                }
            } else {
                // Connect to a random connected room
                $connectedIndex = $connectedRooms[array_rand($connectedRooms)];
                $this->forceConnect($unconnectedIndex, $connectedIndex);
                $connectedRooms[] = $unconnectedIndex;
            }
        }
    }

    private function forceConnect($roomIndex1, $roomIndex2) {
        $room1 = &$this->rooms[$roomIndex1];
        $room2 = &$this->rooms[$roomIndex2];

        // Find the shortest path
        if ($room1['gridX'] == $room2['gridX']) {
            // Same column - connect vertically
            $this->createVerticalConnection($room1, $room2);
        } else if ($room1['gridY'] == $room2['gridY']) {
            // Same row - connect horizontally
            $this->createHorizontalConnection($room1, $room2);
        } else {
            // Need to create an L-shaped path
            // First, choose a random intermediate room
            $intermediateRoom = null;

            // Try to find an available intermediate room
            $potentialRooms = array_filter($this->rooms, function($room) use ($room1, $room2) {
                return ($room['gridX'] == $room1['gridX'] && $room['gridY'] == $room2['gridY']) ||
                    ($room['gridX'] == $room2['gridX'] && $room['gridY'] == $room1['gridY']);
            });

            if (!empty($potentialRooms)) {
                $intermediateRoom = reset($potentialRooms);

                // Connect room1 to intermediate room
                if ($room1['gridX'] == $intermediateRoom['gridX']) {
                    $this->createVerticalConnection($room1, $intermediateRoom);
                } else {
                    $this->createHorizontalConnection($room1, $intermediateRoom);
                }

                // Connect intermediate room to room2
                if ($intermediateRoom['gridX'] == $room2['gridX']) {
                    $this->createVerticalConnection($intermediateRoom, $room2);
                } else {
                    $this->createHorizontalConnection($intermediateRoom, $room2);
                }
            }
        }

        $room1['connected'] = true;
        $room2['connected'] = true;
    }

    private function createHorizontalConnection($room1, $room2) {
        // Ensure room1 is to the left of room2
        if ($room1['gridX'] > $room2['gridX']) {
            $temp = $room1;
            $room1 = $room2;
            $room2 = $temp;
        }

        $startX = $room1['x'] + self::ROOM_SIZE;
        $endX = $room2['x'];
        $y = $room1['y'] + floor(self::ROOM_SIZE / 2);

        for ($x = $startX; $x < $endX; $x++) {
            // Only place corridor if the cell is empty
            if ($this->map[$y][$x] === self::EMPTY_SPACE) {
                $this->map[$y][$x] = self::CORRIDOR;
            }
        }
    }

    private function createVerticalConnection($room1, $room2) {
        // Ensure room1 is above room2
        if ($room1['gridY'] > $room2['gridY']) {
            $temp = $room1;
            $room1 = $room2;
            $room2 = $temp;
        }

        $startY = $room1['y'] + self::ROOM_SIZE;
        $endY = $room2['y'];
        $x = $room1['x'] + floor(self::ROOM_SIZE / 2);

        for ($y = $startY; $y < $endY; $y++) {
            // Only place corridor if the cell is empty
            if ($this->map[$y][$x] === self::EMPTY_SPACE) {
                $this->map[$y][$x] = self::CORRIDOR;
            }
        }
    }

    public function getMap() {
        return $this->map;
    }

    public function displayMap() {
        $output = "";
        foreach ($this->map as $row) {
            $output .= implode('', $row) . PHP_EOL;
        }
        return $output;
    }
}

// Usage
$generator = new RoguelikeMapGenerator();
echo $generator->displayMap();

// Optionally, you can save the map to a file
file_put_contents('roguelike_map.txt', $generator->displayMap());
//?>
