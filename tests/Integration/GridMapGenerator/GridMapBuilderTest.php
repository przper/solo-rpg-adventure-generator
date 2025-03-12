<?php

namespace App\Tests\Integration\GridMapGenerator;

use App\Service\Map\Core\Map;
use App\Service\Map\Grid\GridMapBuilder;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GridMapBuilderTest extends KernelTestCase
{
    private GridMapBuilder $builder;

    protected function setUp(): void
    {
        $this->builder = static::getContainer()->get(GridMapBuilder::class);
    }

    /** @test */
    public function it_builds_map(): void
    {
        $map = $this
            ->builder
            ->setGridHeight(2)
            ->setGridWidth(3)
            ->setRoomSize(1)
            ->setCorridorLength(4)
            ->create();

        $this->assertInstanceOf(Map::class, $map);
    }
}
