<?php

namespace App\Controller;

use App\Enum\MovementDirection;
use App\Service\Game\Game;
use App\Service\Game\Movement;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
            $direction = MovementDirection::from($direction);

            $movement = Movement::new()->add($direction);
            $game->movePlayer($movement);
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
