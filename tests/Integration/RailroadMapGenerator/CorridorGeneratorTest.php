<?php

namespace App\Tests\Integration\RailroadMapGenerator;

use App\Service\Map\Railroad\Corridor;
use App\Service\Map\Railroad\CorridorGenerator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CorridorGeneratorTest extends KernelTestCase
{
    private CorridorGenerator $generator;

    public function setUp(): void
    {
        self::bootKernel();

        $this->generator = static::getContainer()->get(CorridorGenerator::class);
    }

    /** @test */
    public function it_generates_corridors()
    {
        $corridor = $this->generator->generate();

        $this->assertInstanceOf(Corridor::class, $corridor);
    }
}
