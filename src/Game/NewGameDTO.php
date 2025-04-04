<?php

namespace App\Game;

use App\Core\Map\DungeonLength;
use App\Core\Map\MapType;
use App\EncountersPlanning\TTRPGSystem;
use Symfony\Component\Validator\Constraints as Assert;

class NewGameDTO
{
    /** @param positive-int[] $playerLevels */
    public function __construct(
        public DungeonLength $length = DungeonLength::SHORT,
        public MapType $mapType = MapType::Railroad,
        public TTRPGSystem $system = TTRPGSystem::DungeonAndDragons5Edition,
        #[Assert\All(
            new Assert\Positive(),
        )]
        public array $playerLevels = [1, 1, 1, 1],
    ) {
    }
}
