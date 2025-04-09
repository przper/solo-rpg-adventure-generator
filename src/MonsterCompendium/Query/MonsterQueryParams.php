<?php

namespace App\MonsterCompendium\Query;

use App\EncountersPlanning\TTRPGSystem;

final readonly class MonsterQueryParams
{
    public function __construct(
        public TTRPGSystem $system = TTRPGSystem::Shadowdark,
        public ?string $phrase = null,
    ) {
    }
}
