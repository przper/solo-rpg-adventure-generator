<?php

namespace App\Tests\Unit\EncountersPlanning\Shadowdark;

use App\Core\Encounter\Encounter;
use App\Core\Encounter\Enemy;
use App\EncountersPlanning\Shadowdark\EncounterStrategies\BossMonsterEncounterStrategy;
use App\EncountersPlanning\Shadowdark\TreasureGenerator;
use App\EncountersPlanning\TeamChallengeRating;
use App\MonsterCompendium\Entity\ShadowdarkMonster;
use App\MonsterCompendium\ShadowdarkMonsterRepository;
use PHPUnit\Framework\TestCase;

class BossMobEncounterStrategyTest extends TestCase
{
    private BossMonsterEncounterStrategy $sut;

    protected function setUp(): void
    {
        $mockedMonsterRepository = $this->createMock(ShadowdarkMonsterRepository::class);
        $mockedMonsterRepository->method('get')->willReturnCallback(function($min, $max) {
            $result = [];

            foreach (range(1, $max) as $i) {
                if ($i == 1) {
                    $result[] = new ShadowdarkMonster(1, 'Bebok', 11, attacks: ["Spear: 1x 1d6"], totalHitPoints: 4);
                }

                if ($i == 2) {
                    $result[] = new ShadowdarkMonster(2, 'Bebok Warrior', 13, attacks: ["Sword: 1x +1, 1d6"], totalHitPoints: 7);
                }

                if ($i == 3) {
                    $result[] = new ShadowdarkMonster(3, 'Bebok Boss', 13, attacks: ["Greatmace: 1x +2, 1d10"], totalHitPoints: 11);
                    $result[] = new ShadowdarkMonster(3, 'Red Lolok', 12, attacks: ["Claw: 2x +1, 1d6"], totalHitPoints: 11);
                }

                if ($i == 4) {
                    $result[] = new ShadowdarkMonster(4, 'Lime Lolok', 14, attacks: ["Claw: 2x +3, 1d6"], totalHitPoints: 15);
                }
            }

            return $result;
        });

        $this->sut = new BossMonsterEncounterStrategy($mockedMonsterRepository, new TreasureGenerator());
    }

    /**
     * @dataProvider playerLevels
     */
    public function test_it_generates_encounters_matching_players_level(
        TeamChallengeRating $playerLevels,
        int $expectedCombinedMonsterLevel,
        int $expectedMaximumMonsterLevel,
    ): void {
        for ($i = 0; $i < 50; $i++) {
            $encounter = $this->sut->createEncounter($playerLevels);

            $this->assertInstanceOf(Encounter::class, $encounter);

            $combinedMonsterLevel = array_reduce(
                $encounter->getAllEnemies(),
                fn(float $c, Enemy $e) => $c + $e->getChallengeRating(),
                0,
            );

            $this->assertEquals($expectedCombinedMonsterLevel, $combinedMonsterLevel);

            $maxMonsterLevel = max(array_map(fn(Enemy $e) => $e->getChallengeRating(), $encounter->getAllEnemies()));

            $this->assertLessThanOrEqual($expectedMaximumMonsterLevel, $maxMonsterLevel);
        }
    }

    public function playerLevels(): iterable
    {
        yield [TeamChallengeRating::fromLevelsAsIntegers(1, 1), 2, 3];
        yield [TeamChallengeRating::fromLevelsAsIntegers(2, 1), 3, 4];
        yield [TeamChallengeRating::fromLevelsAsIntegers(3, 1), 4, 5];
        yield [TeamChallengeRating::fromLevelsAsIntegers(1, 1, 1, 1), 4, 3];
        yield [TeamChallengeRating::fromLevelsAsIntegers(2, 3, 2, 1), 8, 5];
        yield [TeamChallengeRating::fromLevelsAsIntegers(4, 3, 2, 4), 13, 6];
    }
}
