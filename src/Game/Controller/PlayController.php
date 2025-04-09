<?php

namespace App\Game\Controller;

use App\Game\Game;
use App\Game\Movement;
use App\Game\MovementDirection;
use App\MapRendering\MapRenderer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class PlayController extends AbstractController
{
    public function __construct(
        private MapRenderer $mapRenderer,
    ) {
    }

    #[Route('/play', name: 'game.play', methods: ['GET', 'POST'])]
    public function __invoke(Request $request, SessionInterface $session): Response
    {
        if (! $session->has('game')) {
            return $this->redirectToRoute('game.new');
        }

        /** @var Game $game */
        $game = $session->get('game');

        $action = $request->get('action');

        if ($action === 'move' && $direction = $request->get('direction')) {
            $direction = MovementDirection::from($direction);

            $movement = Movement::new()->add($direction);
            $game->movePlayer($movement);
        }

        if ($action === 'encounter' && $result = $request->get('result')) {
            $game->resolveCurrentEncounter($result);
        }

        $map = $this->mapRenderer->render($game->getMap(), $game);

        $session->set('game', $game);

        return $this->render('play/index.html.twig', [
            'heading' => 'Survive, brave adventurer...',
            'game' => $session->get('game'),
            'map' => $map,
        ]);
    }
}
