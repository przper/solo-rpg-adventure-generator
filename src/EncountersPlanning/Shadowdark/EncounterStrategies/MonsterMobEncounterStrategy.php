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

class MonsterMobEncounterStrategy implements EncounterStrategy
{
    public function __construct(
        private ShadowdarkMonsterRepository $monsterRepository,
        private TreasureGenerator $treasureGenerator,
    ) {
    }

    public function getDungeonRoomType(): DungeonRoomType
    {
        return DungeonRoomType::Monster_Mob;
    }

    public function createEncounter(TeamChallengeRating $playerLevel): Encounter
    {
        $monsters = $this->generateMonsters($playerLevel);
        $enemies = array_map(fn(Monster $m) => $m->toEnemy(), $monsters);

        return new Encounter(
            EncounterDifficulty::MEDIUM,
            $enemies,
            treasures: [
                $this->treasureGenerator->getRandomTreasure($playerLevel->getAveragePlayerLevel()),
            ],
        );
    }

    /** @return Monster[] */
    private function generateMonsters(TeamChallengeRating $playerLevel): array
    {
        /** 1:1 MONSTERS table from Shadowdark core rules, page 193 */
        $monsterLevelTable = [
            0 => 1,
            1 => 1,
            2 => 1,
            3 => 1,
            4 => 3,
            5 => 3,
            6 => 3,
            7 => 5,
            8 => 5,
            9 => 5,
            10 => 7,
        ];
        $monsterLevels = array_map(fn(int $i) => $monsterLevelTable[$i], $playerLevel->toArray());
        sort($monsterLevels);
        $combinedMonsterLevel = array_sum($monsterLevels);

        $matchingMonsterOptions = $this->monsterRepository->get(
            minChallengeRating: $monsterLevels[0],
            maxChallengeRating: $monsterLevels[count($monsterLevels) - 1],
        );

        /** @var array{monsters: Monster[], combined_levels: float} $variants */
        $variants = [];

        for ($i = 0; $i < 100; $i++) {
            $monsters = [];
            $combinedVariantLevels = 0;

            while($combinedVariantLevels < $combinedMonsterLevel) {
                $monster = $matchingMonsterOptions[array_rand($matchingMonsterOptions)];
                $combinedVariantLevels += (float) $monster->getChallengeRating();
                $monsters[] = $monster;
            }

            $variants[] = ['monsters' => $monsters, 'combined_levels' => $combinedVariantLevels];
        }

        foreach ($variants as $i => $variant) {
            if (count($variant['monsters']) === 1) {
                unset($variants[$i]);
            }
        }

        usort($variants, function (array $a, array $b) use ($combinedMonsterLevel) {
            return abs($a['combined_levels'] - $combinedMonsterLevel) <=> abs($b['combined_levels'] - $combinedMonsterLevel);
        });

        return $variants[0]['monsters'];
    }
}
