<?php

namespace App\Controller;

use App\Form\NewGameType;
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
    public function __construct(
        private GameFactory $gameFactory,
    ) {
    }

    #[Route('play/new', name: 'game.new')]
    public function __invoke(Request $request, SessionInterface $session): Response
    {
        $session->remove('game');

        $dto = new NewGameDTO();

        $form = $this->createForm(NewGameType::class, $dto)
            ->add('submit', SubmitType::class, ['label' => 'Begin adventure!']);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $game = $this->gameFactory->create($dto);
            $game->start();
            $session->set('game', $game);

            return $this->redirectToRoute('game.play');
        }

        return $this->render('new/index.html.twig', [
            'form' => $form,
        ]);
    }

}
