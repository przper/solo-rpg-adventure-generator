<?php

namespace App\EncountersPlanning\Shadowdark\EncounterStrategies;

use App\Core\Encounter\Encounter;
use App\Core\Encounter\EncounterDifficulty;
use App\EncountersPlanning\Shadowdark\DungeonRoomType;
use App\EncountersPlanning\Shadowdark\EncounterStrategy;
use App\EncountersPlanning\Shadowdark\TreasureGenerator;
use App\EncountersPlanning\TeamChallengeRating;
use App\MonsterCompendium\Entity\Monster;
use App\MonsterCompendium\ShadowdarkMonsterRepository;

class BossMonsterEncounterStrategy implements EncounterStrategy
{
    public function __construct(
        private ShadowdarkMonsterRepository $monsterRepository,
        private TreasureGenerator $treasureGenerator,
    ) {
    }

    public function getDungeonRoomType(): DungeonRoomType
    {
        return DungeonRoomType::Boss_Monster;
    }

    public function createEncounter(TeamChallengeRating $playerLevel): Encounter
    {
        $monsters = $this->generateMonsters($playerLevel);
        $enemies = array_map(fn(Monster $m) => $m->toEnemy(), $monsters);

        return new Encounter(
            EncounterDifficulty::DEADLY,
            $enemies,
            treasures: [
                $this->treasureGenerator->getRandomTreasure($playerLevel->getAveragePlayerLevel()),
            ],
        );
    }

    /** @return Monster[] */
    private function generateMonsters(TeamChallengeRating $playerLevel): array
    {
        $maxMonsterLevel = max($playerLevel->toArray()) + 2;
        $combinedPlayerLevels = array_sum($playerLevel->toArray());

        $matchingMonsterOptions = $this->monsterRepository->get(
            maxChallengeRating: $maxMonsterLevel,
        );

        /** @var array{monsters: Monster[], combined_levels: float} $variants */
        $variants = [];

        for ($i = 0; $i < 50; $i++) {
            $monsters = [];
            $combinedVariantLevels = 0;

            while($combinedVariantLevels < $combinedPlayerLevels) {
                $monster = $matchingMonsterOptions[array_rand($matchingMonsterOptions)];
                $combinedVariantLevels += (float) $monster->getChallengeRating();
                $monsters[] = $monster;
            }

            $variants[] = ['monsters' => $monsters, 'combined_levels' => $combinedVariantLevels];
        }

        usort($variants, function (array $a, array $b) use ($combinedPlayerLevels) {
            return abs($a['combined_levels'] - $combinedPlayerLevels) <=> abs($b['combined_levels'] - $combinedPlayerLevels);
        });

        return $variants[0]['monsters'];
    }
}
