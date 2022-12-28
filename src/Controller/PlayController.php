<?php

namespace App\Controller;

use App\Service\Game\GameFactory;
use App\Service\Game\PlayerPosition;
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
        private GameFactory $gameFactory
    ) {
        //
    }

    #[Route('/play/simple', name: 'app_play_simple')]
    public function simple(SessionInterface $session): Response
    {
        $rowsCount = 15;
        $columnsCount = 15;
        $roomsCount = 15;

        $session->remove('game');

        $game = $session->get(
            'game',
            $this->gameFactory
                ->setMapBuilder(
                    $this->roguelikeGenerator
                    ->setRowsCount($rowsCount)
                    ->setColumnsCount($columnsCount)
                    ->setRoomsCount($roomsCount)
                )
                ->create()
        );

        return $this->render('play/index.html.twig', [
            'heading' => 'Simple Dungeon Generator (WIP)',
            'template' => 'map-generator/simple.html.twig',
            'game' => $game,
        ]);
    }

    #[Route('/play/railroad', name: 'app_play_railroad')]
    public function railroad(Request $request, SessionInterface $session): Response
    {
        $roomsCount = 5;

        $game = $session->get(
            'game',
            $this->gameFactory
                ->setMapBuilder(
                    $this->railroadGenerator
                        ->setRoomsCount($roomsCount)
                        ->setMinCorridorLength(2)
                        ->setMaxCorridorLength(5)
                )
                ->create()
        );

        if ($direction = $request->get('direction')) {
            /** @var PlayerPosition $position */
            $position = $session->get('game')->getPosition();

            match ($direction) {
                'forward' => $position->move(1, 0),
                'backward' => $position->move(-1, 0)
            };
        }

        $session->set('game', $game);

        return $this->render('play/index.html.twig', [
            'heading' => 'Railroad Dungeon Generator (WIP)',
            'template' => 'map-generator/railroad.html.twig',
            'game' => $session->get('game'),
        ]);
    }
}
