<?php

namespace App\Controller;

use App\Service\Game\Game;
use App\Service\Game\GameFactory;
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
        private MapRenderer $mapRenderer,
    ) {
        //
    }

    #[Route('play/new', name: 'app_new_game')]
    public function new(Request $request, SessionInterface $session): Response
    {
        $session->remove('game');

        $type = $request->get('type');

        if (is_null($type)) {
            return $this->render('play/new.html.twig', [
                'heading' => 'Select Map Type'
            ]);
        }

        if ($type === 'roguelike') {
            $rowsCount = 20;
            $columnsCount = 20;
            $roomsCount = 5;

            $game = $this->gameFactory
                ->setMapBuilder(
                    $this->roguelikeGenerator
                    ->setRowsCount($rowsCount)
                    ->setColumnsCount($columnsCount)
                    ->setRoomsCount($roomsCount)
                )
                ->create();
        }

        if ($type === 'railroad') {
            $roomsCount = 5;

            $game = $this->gameFactory
                ->setMapBuilder(
                    $this->railroadGenerator
                        ->setRoomsCount($roomsCount)
                        ->setMinCorridorLength(2)
                        ->setMaxCorridorLength(5)
                )
                ->create();
        }

        $game->start();

        $session->set('game', $game);

        return $this->redirectToRoute('app_play');
    }

    #[Route('/play', name: 'app_play')]
    public function play(Request $request, SessionInterface $session): Response
    {
        if (! $session->has('game')) {
            return $this->redirectToRoute('app_new_game');
        }

        /** @var Game $game */
        $game = $session->get('game');

        if ($direction = $request->get('direction')) {
            match ($direction) {
                'forward' => $game->movePlayerByIntegers(1, 0),
                'backward' => $game->movePlayerByIntegers(-1, 0)
            };
        }

        $map = $this->mapRenderer->render($game->getMap(), $game);

        $session->set('game', $game);

        return $this->render('play/index.html.twig', [
            'heading' => 'Survive, brave adventurer...',
            'game' => $session->get('game'),
            'map' => $map
        ]);
    }
}
