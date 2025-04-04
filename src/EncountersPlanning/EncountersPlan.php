<?php

namespace App\EncountersPlanning;

use App\Core\Encounter\Encounter;
use App\Core\Encounter\EncounterDifficulty;

final readonly class EncountersPlan
{
    /**
     * @param Encounter[] $encounters
     */
    public function __construct(
        public array $encounters = [],
    ) {
    }

    public function getEncountersByDifficulty(EncounterDifficulty ...$difficulty): array
    {
        return array_values(array_filter(
            $this->encounters,
            fn (Encounter $e) => in_array($e->getDifficulty(), $difficulty))
        );
    }

    /**
     * @param 'DESC'|'ASC' $direction
     * @return Encounter[]
     */
    public function getEncountersSortedByDifficulty(string $direction = 'DESC'): array
    {
        $result = array_values($this->encounters);

        usort($result, function (Encounter $a, Encounter $b) use ($direction) {
            $order = [
                'DESC' => [EncounterDifficulty::DEADLY, EncounterDifficulty::HARD, EncounterDifficulty::MEDIUM, EncounterDifficulty::EASY],
                'ASC' => [EncounterDifficulty::EASY, EncounterDifficulty::MEDIUM, EncounterDifficulty::HARD, EncounterDifficulty::DEADLY],
            ];

            $difficultyOrder = $order[$direction] ?? $order['DESC'];

            return array_search($a->getDifficulty(), $difficultyOrder) <=> array_search($b->getDifficulty(), $difficultyOrder);
        });

        return $result;
    }
}
