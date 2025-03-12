<?php

namespace App\Controller;

use App\Service\Game\Game;
use App\Service\Game\GameFactory;
use App\Service\Map\Grid\GridMapBuilder;
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
        private GridMapBuilder $gridMapBuilder,
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
            $deltaX = 0;
            $deltaY = 0;

            // Determine movement deltas based on direction
            match ($direction) {
                'forward', 'east' => $deltaX = 1,
                'backward', 'west' => $deltaX = -1,
                'north' => $deltaY = -1,  // Moving north decreases y-coordinate (going up in the grid)
                'south' => $deltaY = 1    // Moving south increases y-coordinate (going down in the grid)
            };

            // Calculate the new position coordinates
            $currentCoords = clone $game->getPlayerPosition()->getCoordinates();
            $targetCoords = clone $currentCoords;
            $targetCoords->moveBy($deltaX, $deltaY);

            // Only move if there's a valid cell at the target coordinates
            if ($game->getMap()->getCell($targetCoords) !== null) {
                $game->movePlayerByIntegers($deltaX, $deltaY);
            }
        }

        $map = $this->mapRenderer->render($game->getMap(), $game);

        // Check possible moves for template
        $currentCoords = $game->getPlayerPosition()->getCoordinates();
        $canMoveNorth = $this->canMoveInDirection($game, 0, -1);
        $canMoveSouth = $this->canMoveInDirection($game, 0, 1);
        $canMoveEast = $this->canMoveInDirection($game, 1, 0);
        $canMoveWest = $this->canMoveInDirection($game, -1, 0);

        $session->set('game', $game);

        dump($game);

        return $this->render('play/index.html.twig', [
            'heading' => 'Survive, brave adventurer...',
            'game' => $session->get('game'),
            'map' => $map,
            'canMoveNorth' => $canMoveNorth,
            'canMoveSouth' => $canMoveSouth,
            'canMoveEast' => $canMoveEast,
            'canMoveWest' => $canMoveWest
        ]);
    }

    private function canMoveInDirection(Game $game, int $deltaX, int $deltaY): bool
    {
        $currentCoords = clone $game->getPlayerPosition()->getCoordinates();
        $targetCoords = clone $currentCoords;
        $targetCoords->moveBy($deltaX, $deltaY);

        return $game->getMap()->getCell($targetCoords) !== null;
    }
}
