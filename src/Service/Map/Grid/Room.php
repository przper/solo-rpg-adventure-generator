<?php

namespace App\Service\Map\Grid;

use App\Helper\Coordinates;

class Room extends Cell
{
    public const TYPE = 'ROOM';
    
    /** @var array<string, string> Connections in each direction (north, east, south, west) */
    private array $connections = [];

    public function __construct(Coordinates $coordinates)
    {
        parent::__construct($coordinates, self::TYPE);
    }
    
    /**
     * Add a connection in a specific direction
     * 
     * @param string $direction north, east, south, or west
     * @param string $type The type of cell this connects to
     */
    public function addConnection(string $direction, string $type): void
    {
        $this->connections[$direction] = $type;
    }
    
    /**
     * Get all connections
     * 
     * @return array<string, string> Map of directions to connection types
     */
    public function getConnections(): array
    {
        return $this->connections;
    }
    
    /**
     * Check if there's a connection in a specific direction
     */
    public function hasConnection(string $direction): bool
    {
        return isset($this->connections[$direction]);
    }
}