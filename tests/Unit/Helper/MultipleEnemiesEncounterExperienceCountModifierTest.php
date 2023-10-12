<?php

namespace App\Tests\Unit\Helper;

use App\Helper\MultipleEnemiesEncounterExperienceCountModifier;
use PHPUnit\Framework\TestCase;

class MultipleEnemiesEncounterExperienceCountModifierTest extends TestCase
{
    /** @dataProvider multiplier */
    public function test_get_multiplier(int $enemyCount, float $expected): void
    {
        $this->assertEquals($expected, MultipleEnemiesEncounterExperienceCountModifier::getMultiplier($enemyCount));
    }

    public function multiplier(): array
    {
        return [
            [0, 0],
            [1, 1.0],
            [2, 1.5],
            [3, 2.0],
            [7, 2.5],
            [11, 3.0],
            [15, 4.0],
            [20, 4.0],
        ];
    }

    public function test_adjust_experience_points(): void
    {
        $this->assertEquals(100, MultipleEnemiesEncounterExperienceCountModifier::adjustExperiencePoints(1, 100));
        $this->assertEquals(150, MultipleEnemiesEncounterExperienceCountModifier::adjustExperiencePoints(2, 100));
    }
}
