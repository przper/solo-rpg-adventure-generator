<?php

namespace App\Controller;

use App\Service\RailroadGenerator\RailroadMapBuilder;
use App\Service\SimpleGenerator\SimpleMapBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlayController extends AbstractController
{
    public function __construct(
        private SimpleMapBuilder $simpleGenerator,
        private RailroadMapBuilder $railroadGenerator,
    ) {
        //
    }

    #[Route('/play/simple', name: 'app_play_simple')]
    public function simple(): Response
    {
        $rowsCount = 15;
        $columnsCount = 15;
        $roomsCount = 15;

        $map = $this->simpleGenerator
            ->setRowsCount($rowsCount)
            ->setColumnsCount($columnsCount)
            ->setRoomsCount($roomsCount)
            ->create();

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
        $roomsCount = 4;

        $map = $this->railroadGenerator
            ->setRoomsCount($roomsCount)
            ->setMinCorridorLength(2)
            ->setMaxCorridorLength(5)
            ->create();

        return $this->render('play/index.html.twig', [
            'heading' => 'Railroad Dungeon Generator (WIP)',
            'map' => $map,
            'template' => 'map-generator/railroad.html.twig',
        ]);
    }
}
