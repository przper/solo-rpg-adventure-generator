<?php

namespace App\Tests\Integration\MapRenderer;

use App\Service\MapRenderer\MapRender;
use App\Service\MapRenderer\MapRenderer;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Tests\Integration\MapRenderer\Fixtures\DummyMap;

class MapRendererTest extends KernelTestCase
{
    /** @test */
    public function it_renders_map()
    {
        /** @var MapRenderer $renderer */
        $renderer = static::getContainer()->get(MapRenderer::class);

        $render = $renderer->render(new DummyMap());

        $this->assertInstanceOf(MapRender::class, $render);
    }
}
