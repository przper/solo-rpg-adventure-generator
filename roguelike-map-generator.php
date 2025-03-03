<?php

/**
 * Roguelike Map Generator
 *
 * Generates a 2D matrix representing a roguelike dungeon with:
 * - R: Rooms
 * - C: Corridors
 * - E: Empty Space
 */

class RoguelikeMapGenerator {
    // Map tile constants
    const ROOM = '#';
    const EMPTY = ' ';
    const CORRIDOR = '*';

    // Map dimensions
    private $width;
    private $height;
    // Map matrix
    private $map;
    // Rooms data
    private $rooms = [];
    // Min and max room sizes
    private $minRoomSize = 3;
    private $maxRoomSize = 8;
    // Max number of rooms to attempt to place
    private $maxRooms = 15;

    /**
     * Constructor
     */
    public function __construct($width = 50, $height = 30) {
        $this->width = $width;
        $this->height = $height;
        $this->initializeMap();
    }

    /**
     * Initialize map with empty spaces
     */
    private function initializeMap() {
        $this->map = array_fill(0, $this->height, array_fill(0, $this->width, self::EMPTY));
    }

    /**
     * Generate the roguelike map
     */
    public function generate() {
        // Place rooms
        $this->placeRooms();

        // Connect rooms with corridors
        $this->connectRooms();

        return $this->map;
    }

    /**
     * Place random rooms on the map
     */
    private function placeRooms() {
        $attempts = 0;
        $roomCount = 0;

        // Try to place rooms up to a maximum number of attempts
        while ($roomCount < $this->maxRooms && $attempts < 100) {
            $attempts++;

            // Random room dimensions
            $roomWidth = rand($this->minRoomSize, $this->maxRoomSize);
            $roomHeight = rand($this->minRoomSize, $this->maxRoomSize);

            // Random position (leaving a 1-tile border)
            $x = rand(1, $this->width - $roomWidth - 1);
            $y = rand(1, $this->height - $roomHeight - 1);

            // Create a new room
            $newRoom = [
                'x' => $x,
                'y' => $y,
                'width' => $roomWidth,
                'height' => $roomHeight,
                'centerX' => floor($x + $roomWidth / 2),
                'centerY' => floor($y + $roomHeight / 2)
            ];

            // Check if the room overlaps with any existing room
            $overlap = false;
            foreach ($this->rooms as $room) {
                if ($this->roomsOverlap($newRoom, $room)) {
                    $overlap = true;
                    break;
                }
            }

            // If no overlap, add the room to the map
            if (!$overlap) {
                $this->addRoomToMap($newRoom);
                $this->rooms[] = $newRoom;
                $roomCount++;
            }
        }
    }

    /**
     * Check if two rooms overlap
     */
    private function roomsOverlap($room1, $room2) {
        // Add a margin of 1 to avoid rooms touching
        return !($room1['x'] + $room1['width'] + 1 <= $room2['x'] ||
            $room2['x'] + $room2['width'] + 1 <= $room1['x'] ||
            $room1['y'] + $room1['height'] + 1 <= $room2['y'] ||
            $room2['y'] + $room2['height'] + 1 <= $room1['y']);
    }

    /**
     * Add a room to the map
     */
    private function addRoomToMap($room) {
        for ($y = $room['y']; $y < $room['y'] + $room['height']; $y++) {
            for ($x = $room['x']; $x < $room['x'] + $room['width']; $x++) {
                $this->map[$y][$x] = self::ROOM;
            }
        }
    }

    /**
     * Connect rooms with corridors
     */
    private function connectRooms() {
        // Connect each room to the next one
        for ($i = 0; $i < count($this->rooms) - 1; $i++) {
            $roomA = $this->rooms[$i];
            $roomB = $this->rooms[$i + 1];

            $this->createCorridor($roomA['centerX'], $roomA['centerY'],
                $roomB['centerX'], $roomB['centerY']);
        }
    }

    /**
     * Create an L-shaped corridor between two points
     */
    private function createCorridor($startX, $startY, $endX, $endY) {
        // Randomly decide which direction to go first (horizontal or vertical)
        if (rand(0, 1) === 0) {
            // First horizontal, then vertical
            $this->createHorizontalCorridor($startX, $endX, $startY);
            $this->createVerticalCorridor($startY, $endY, $endX);
        } else {
            // First vertical, then horizontal
            $this->createVerticalCorridor($startY, $endY, $startX);
            $this->createHorizontalCorridor($startX, $endX, $endY);
        }
    }

    /**
     * Create a horizontal corridor
     */
    private function createHorizontalCorridor($startX, $endX, $y) {
        $start = min($startX, $endX);
        $end = max($startX, $endX);

        for ($x = $start; $x <= $end; $x++) {
            if ($this->map[$y][$x] === self::EMPTY) {
                $this->map[$y][$x] = self::CORRIDOR;
            }
        }
    }

    /**
     * Create a vertical corridor
     */
    private function createVerticalCorridor($startY, $endY, $x) {
        $start = min($startY, $endY);
        $end = max($startY, $endY);

        for ($y = $start; $y <= $end; $y++) {
            if ($this->map[$y][$x] === self::EMPTY) {
                $this->map[$y][$x] = self::CORRIDOR;
            }
        }
    }

    /**
     * Display the map as a string
     */
    public function display() {
        $mapString = '';

        for ($y = 0; $y < $this->height; $y++) {
            for ($x = 0; $x < $this->width; $x++) {
                $mapString .= $this->map[$y][$x];
            }
            $mapString .= "\n";
        }

        return $mapString;
    }
}

// Example usage
$mapGenerator = new RoguelikeMapGenerator(60, 30);
$mapGenerator->generate();
echo $mapGenerator->display();

// To run this from command line: php filename.php
