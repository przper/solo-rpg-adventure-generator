<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Map\Railroad\RailroadMapBuilder;
use App\Service\Map\Roguelike\RoguelikeMapBuilder;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PlayController extends AbstractController
{
    public function __construct(
        private RoguelikeMapBuilder $roguelikeGenerator,
        private RailroadMapBuilder $railroadGenerator,
    ) {
        //
    }

    #[Route('/play/simple', name: 'app_play_simple')]
    public function simple(SessionInterface $session): Response
    {
        $rowsCount = 15;
        $columnsCount = 15;
        $roomsCount = 15;

        $map = $this->roguelikeGenerator
            ->setRowsCount($rowsCount)
            ->setColumnsCount($columnsCount)
            ->setRoomsCount($roomsCount)
            ->create();

        $position = $session->get('position', 0);

        return $this->render('play/index.html.twig', [
            'heading' => 'Simple Dungeon Generator (WIP)',
            'rows_count' => $rowsCount,
            'columns_count' => $columnsCount,
            'template' => 'map-generator/simple.html.twig',
            'map' => $map,
            'position' => $position
        ]);
    }

    #[Route('/play/railroad', name: 'app_play_railroad')]
    public function railroad(Request $request, SessionInterface $session): Response
    {
        $roomsCount = 5;

        if (! $session->has('map')) {
            $map = $this->railroadGenerator
                ->setRoomsCount($roomsCount)
                ->setMinCorridorLength(2)
                ->setMaxCorridorLength(5)
                ->create();

            $session->set('map', $map);
        }

        $position = $session->get('position', 0);

        if ($direction = $request->get('direction')) {
            match ($direction) {
                'forward' => $position++,
                'backward' => $position--
            };

            $session->set('position', $position);
        }

        return $this->render('play/index.html.twig', [
            'heading' => 'Railroad Dungeon Generator (WIP)',
            'map' => $session->get('map'),
            'template' => 'map-generator/railroad.html.twig',
            'position' => $position,
        ]);
    }
}
