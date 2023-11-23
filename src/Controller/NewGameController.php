<?php

namespace App\Controller;

use App\Form\GameType;
use App\Service\Game\GameFactory;
use App\Service\Game\NewGameDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class NewGameController extends AbstractController
{
    #[Route('/new', name: 'new_game')]
    public function __invoke(
        Request $request,
        SessionInterface $session,
        GameFactory $gameFactory,
    ): Response
    {
        $session->remove('game');

        $newGame = new NewGameDTO();

        $form = $this
            ->createForm(GameType::class, $newGame)
            ->add('submit', SubmitType::class)
        ;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $game = $gameFactory->create($newGame);
            $game->start();

            $session->set('game', $game);

            return $this->redirectToRoute('play');
        }

        return $this->render('play/new.html.twig', [
            'form' => $form,
        ]);
    }

}
