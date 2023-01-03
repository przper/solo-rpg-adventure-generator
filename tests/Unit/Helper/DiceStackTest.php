<?php

namespace App\Tests\Unit\Helper;

use App\Helper\DiceStack;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Exception\InvalidParameterException;

class DiceStackTest extends TestCase
{
    /** @test */
    public function it_has_dice_count_and_type()
    {
        $diceStack = new DiceStack();
        $diceStack->setDicesCount(1);
        $diceStack->setDiceType(DiceStack::TYPE_D20);

        $this->assertIsNumeric($diceStack->getDicesCount());
        $this->assertEquals(1, $diceStack->getDicesCount());
        $this->assertIsString($diceStack->getDiceType());
        $this->assertContains($diceStack->getDiceType(), DiceStack::getTypes());
        $this->assertEquals("d20", $diceStack->getDiceType());
    }

    /** @test */
    public function it_can_be_created_from_integers()
    {
        $diceStack = DiceStack::fromIntegers(1, 20);

        $this->assertEquals(1, $diceStack->getDicesCount());
        $this->assertEquals("d20", $diceStack->getDiceType());
    }

    /**
     * @test 
     * @dataProvider fromIntegerValidationData
     */
    public function creation_from_integer_validation(int $count, int $sides)
    {
        $this->expectException(InvalidParameterException::class);
        DiceStack::fromIntegers($count, $sides);
    }

    public function fromIntegerValidationData(): array
    {
        return [
            //DICE_COUNT, DICE_SIDES
            [1, 17],
            [-1, 20],
        ];
    }

    /** @test */
    public function it_can_be_created_from_string()
    {
        $diceStack1 = DiceStack::fromString("d20");

        $this->assertEquals(1, $diceStack1->getDicesCount());
        $this->assertEquals("d20", $diceStack1->getDiceType());

        $diceStack2 = DiceStack::fromString("6d6");

        $this->assertEquals(6, $diceStack2->getDicesCount());
        $this->assertEquals("d6", $diceStack2->getDiceType());
    }

    /**
     * @test
     * @dataProvider fromStringValidationData
     */
    public function creation_from_string_validation(string $text)
    {
        $this->expectException(InvalidParameterException::class);
        DiceStack::fromString($text);
    }

    public function fromStringValidationData(): array
    {
        return [
            //TEXT
            ["rdtfygbhij"],
            ["1d"],
            ["d"],
            ["d17"],
            ["17d17"],
            ["d177"],
            ["17d177"],
        ];
    }

    /** @test */
    public function it_can_be_printed()
    {
        $diceStack = DiceStack::fromString("d20");

        $this->assertEquals("1d20", $diceStack);
    }

    /** @test */
    public function it_can_be_rolled()
    {
        $diceStack = DiceStack::fromString("2d2");

        $this->assertGreaterThan(1, $diceStack->roll());
    }
}