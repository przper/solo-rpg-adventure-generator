<?php

namespace App\Controller;

use App\Service\Game\Game;
use App\Service\Game\GameFactory;
use App\Service\Game\PlayerPosition;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Map\Railroad\RailroadMapBuilder;
use App\Service\Map\Roguelike\RoguelikeMapBuilder;
use App\Service\MapRenderer\MapRenderer;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PlayController extends AbstractController
{
    public function __construct(
        private RoguelikeMapBuilder $roguelikeGenerator,
        private RailroadMapBuilder $railroadGenerator,
        private GameFactory $gameFactory,
        private MapRenderer $mapRenderer
    ) {
        //
    }

    #[Route('/play/simple', name: 'app_play_simple')]
    public function simple(SessionInterface $session): Response
    {
        $rowsCount = 5;
        $columnsCount = 5;
        $roomsCount = 5;

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

        $map = $this->mapRenderer->render($game->getMap(), $game->getPlayerPosition());

        return $this->render('play/index.html.twig', [
            'heading' => 'Simple Dungeon Generator (WIP)',
            'template' => 'map-generator/simple.html.twig',
            'game' => $game,
            'map' => $map
        ]);
    }

    #[Route('/play/railroad', name: 'app_play_railroad')]
    public function railroad(Request $request, SessionInterface $session): Response
    {
        $roomsCount = 5;

        /** @var Game $game */
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
            match ($direction) {
                'forward' => $game->movePlayer(1, 0),
                'backward' => $game->movePlayer(-1, 0)
            };
        }

        $map = $this->mapRenderer->render($game->getMap(), $game->getPlayerPosition());

        $session->set('game', $game);

        return $this->render('play/index.html.twig', [
            'heading' => 'Railroad Dungeon Generator (WIP)',
            'template' => 'map-generator/railroad.html.twig',
            'game' => $session->get('game'),
            'map' => $map
        ]);
    }
}
