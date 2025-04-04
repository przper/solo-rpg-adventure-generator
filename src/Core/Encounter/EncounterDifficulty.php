<?php

namespace App\Core\Encounter;

enum EncounterDifficulty: string
{
    case EASY = 'easy';
    case MEDIUM = 'medium';
    case HARD = 'hard';
    case DEADLY = 'deadly';
}
