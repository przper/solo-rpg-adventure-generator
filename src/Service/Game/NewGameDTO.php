<?php

namespace App\Service\Game;

use App\Enum\DungeonLength;

class NewGameDTO
{
    private string $type = 'railroad';
    private DungeonLength $length = DungeonLength::MEDIUM;

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getLength(): DungeonLength
    {
        return $this->length;
    }

    public function setLength(DungeonLength $length): void
    {
        $this->length = $length;
    }
}
