<?php

namespace App\Tests\RailroadMapGenerator;

use App\Service\RailroadGenerator\Corridor;
use App\Service\RailroadGenerator\CorridorGenerator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CorridorGeneratorTest extends KernelTestCase
{ 
    private CorridorGenerator $generator;

    public function setUp(): void
    {
        self::bootKernel();

        $container = static::getContainer();

        $this->generator = $container->get(CorridorGenerator::class);
    }

    /** @test */
    public function it_generates_corridors()
    {
        $corridor = $this->generator->generate();

        $this->assertInstanceOf(Corridor::class, $corridor);
    }
}
