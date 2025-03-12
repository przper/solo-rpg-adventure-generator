<?php

namespace App\Controller;

use App\Service\Game\Game;
use App\Service\Game\GameFactory;
use App\Service\Map\Grid\GridMapBuilder;
use App\Service\Map\Railroad\RailroadMapBuilder;
use App\Service\Map\Roguelike\RoguelikeMapBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class NewGameController extends AbstractController
{
    public function __construct(
        private RoguelikeMapBuilder $roguelikeGenerator,
        private RailroadMapBuilder $railroadGenerator,
        private GridMapBuilder $gridMapBuilder,
        private GameFactory $gameFactory,
    ) {
    }

    #[Route('play/new', name: 'game.new')]
    public function __invoke(Request $request, SessionInterface $session): Response
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
                        ->setMaxRoomsCount($roomsCount)
                        ->setMinCorridorLength(2)
                        ->setMaxCorridorLength(5)
                )
                ->create();
        }

        if ($type === 'grid') {
            $game = $this->gameFactory
                ->setMapBuilder(
                    $this->gridMapBuilder
                        ->setGridWidth(5)
                        ->setGridHeight(4)
                        ->setRoomSize(1)
                        ->setCorridorLength(4)
                )
                ->create();
        }

        if (!isset($game) || !$game instanceof Game) {
            throw new \InvalidArgumentException("Not known dungeon type: $type");
        }

        $game->start();

        $session->set('game', $game);

        return $this->redirectToRoute('game.play');
    }

}
