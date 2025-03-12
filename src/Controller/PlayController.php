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
        private MapRenderer $mapRenderer,
    ) {
    }

    #[Route('/play', name: 'game.play')]
    public function __invoke(Request $request, SessionInterface $session): Response
    {
        if (! $session->has('game')) {
            return $this->redirectToRoute('game.new');
        }

        /** @var Game $game */
        $game = $session->get('game');

        if ($direction = $request->get('direction')) {
            match ($direction) {
                'forward', 'east' => $game->movePlayerByIntegers(1, 0),
                'backward', 'west' => $game->movePlayerByIntegers(-1, 0),
                'north' => $game->movePlayerByIntegers(0, -1),  // Moving north decreases y-coordinate (going up in the grid)
                'south' => $game->movePlayerByIntegers(0, 1)    // Moving south increases y-coordinate (going down in the grid)
            };
        }

        $map = $this->mapRenderer->render($game->getMap(), $game);

        $session->set('game', $game);

        dump($game);

        return $this->render('play/index.html.twig', [
            'heading' => 'Survive, brave adventurer...',
            'game' => $session->get('game'),
            'map' => $map,
        ]);
    }
}
