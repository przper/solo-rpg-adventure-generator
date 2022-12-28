<?php

namespace App\Controller;

use App\Service\RailroadGenerator\RailroadGenerator;
use App\Service\SimpleGenerator\SimpleMapGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlayController extends AbstractController
{
    public function __construct(
        private SimpleMapGenerator $simpleGenerator,
        private RailroadGenerator $railroadGenerator,
    ) {
        //
    }

    #[Route('/play/simple', name: 'app_play_simple')]
    public function simple(): Response
    {
        $rowsCount = 15;
        $columnsCount = 15;
        $roomsCount = 15;

        $map = $this->simpleGenerator->create($rowsCount, $columnsCount, $roomsCount);

        return $this->render('play/index.html.twig', [
            'heading' => 'Simple Dungeon Generator (WIP)',
            'rows_count' => $rowsCount,
            'columns_count' => $columnsCount,
            'template' => 'map-generator/simple.html.twig',
            'map' => $map,
        ]);
    }

    #[Route('/play/railroad', name: 'app_play_railroad')]
    public function railroad(): Response
    {
        $rowsCount = 10;
        $roomsCount = 4;

        $map = $this->railroadGenerator->create($rowsCount, 1, $roomsCount);

        return $this->render('play/index.html.twig', [
            'heading' => 'Railroad Dungeon Generator (WIP)',
            'rows_count' => $rowsCount,
            'map' => $map,
            'template' => 'map-generator/railroad.html.twig',
        ]);
    }
}
