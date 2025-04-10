<?php

namespace App\Tests\Unit\EncountersPlanning\Shadowdark;

use App\Core\Encounter\Encounter;
use App\EncountersPlanning\Shadowdark\EncounterStrategies\MonsterMobEncounterStrategy;
use App\EncountersPlanning\Shadowdark\TreasureGenerator;
use App\EncountersPlanning\TeamChallengeRating;
use App\MonsterCompendium\Entity\ShadowdarkMonster;
use App\MonsterCompendium\ShadowdarkMonsterRepository;
use PHPUnit\Framework\TestCase;

class MonsterMobEncounterStrategyTest extends TestCase
{
    private MonsterMobEncounterStrategy $sut;

    protected function setUp(): void
    {
        $mockedMonsterRepository = $this->createMock(ShadowdarkMonsterRepository::class);
        $mockedMonsterRepository->method('get')->willReturn([
            new ShadowdarkMonster(1, 'Bebok', 11, attacks: ["Spear: 1x 1d6"], totalHitPoints: 4),
            new ShadowdarkMonster(2, 'Bebok Warrior', 13, attacks: ["Sword: 1x +1, 1d6"], totalHitPoints: 7),
            new ShadowdarkMonster(3, 'Bebok Boss', 13, attacks: ["Greatmace: 1x +2, 1d10"], totalHitPoints: 11),
        ]);

        $this->sut = new MonsterMobEncounterStrategy($mockedMonsterRepository, new TreasureGenerator());
    }

    /**
     * @dataProvider playerLevels
     */
    public function test_it_generates_encounters_matching_players_level(TeamChallengeRating $playerLevels): void
    {
        for ($i = 0; $i < 50; $i++) {
            $encounter = $this->sut->createEncounter($playerLevels);

            $this->assertInstanceOf(Encounter::class, $encounter);
            $this->assertGreaterThan(1, count($encounter->getAllEnemies()));
        }
    }

    public function playerLevels(): iterable
    {
        yield [TeamChallengeRating::fromLevelsAsIntegers(1, 1, 1, 1)];
    }
}
