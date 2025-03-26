<?php

namespace App\Enum;

enum EncounterDifficulty: string
{
    case EASY = 'easy';
    case MEDIUM = 'medium';
    case HARD = 'hard';
    case DEADLY = 'deadly';
}
