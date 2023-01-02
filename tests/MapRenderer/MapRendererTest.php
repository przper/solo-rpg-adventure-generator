<?php

namespace App\Tests\MapRenderer;

use App\Service\MapRenderer\MapRenderer;
use App\Tests\MapRenderer\Fixtures\DummyMap;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MapRendererTest extends KernelTestCase
{
    /** @test */
    public function it_renders_map()
    {
        static::bootKernel();

        /** @var MapRenderer $renderer */
        $renderer = static::getContainer()->get(MapRenderer::class);

        $render = $renderer->render(new DummyMap());

        $this->assertIsString($render);
    }
}
