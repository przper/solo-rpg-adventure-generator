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

    /**
     * @test
     * @dataProvider fromStringData
     */
    public function it_can_be_created_from_string(
        string $text,
        int $diceCount,
        string $diceType,
        int $rollModifier
    ) {
        $diceStack = DiceStack::fromString($text);

        $this->assertEquals($diceCount, $diceStack->getDicesCount());
        $this->assertEquals($diceType, $diceStack->getDiceType());
        $this->assertEquals($rollModifier, $diceStack->getRollModifier());
    }

    public function fromStringData(): array
    {
        return [
            // TEXT, DICE_COUNT, DICE_TYPE, ROLL_MODIFIER
            ['d20', 1, 'd20', 0],
            ['6d6', 6, 'd6', 0],
            ['1d20+3', 1, 'd20', 3],
            ['1d6-3', 1, 'd6', -3],
        ];
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

    /**
     * @test
     * @dataProvider printData
     */
    public function it_can_be_printed(string $text, string $toString)
    {
        $diceStack = DiceStack::fromString($text);

        $this->assertEquals($toString, $diceStack);
    }

    public function printData(): array
    {
        return [
            //TEXT, TO_STRING
            ["d20", "1d20+0"],
            ["d20+1", "1d20+1"],
            ["d20-1", "1d20-1"],
        ];
    }

    /** @test */
    public function it_can_be_rolled()
    {
        $doubleDiceStack = DiceStack::fromString("2d2");
        $this->assertGreaterThan(1, $doubleDiceStack->roll());
        $this->assertLessThan(5, $doubleDiceStack->roll());

        $diceStackWithModifier = DiceStack::fromString("1d2+10");
        $this->assertGreaterThan(10, $diceStackWithModifier->roll());
        $this->assertLessThan(13, $diceStackWithModifier->roll());
    }
}