<?php

namespace App\Service\Game;

use App\Enum\DungeonLength;
use App\Enum\MapType;
use App\Enum\TTRPGSystem;

class NewGameDTO
{
    public function __construct(
        public DungeonLength $length = DungeonLength::SHORT,
        public MapType $mapType = MapType::Railroad,
        public TTRPGSystem $system = TTRPGSystem::DungeonAndDragons5Edition,
    ) {
    }
}
