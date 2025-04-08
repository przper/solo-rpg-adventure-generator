<?php

namespace App\MonsterCompendium\Controller;

use App\MonsterCompendium\ShadowdarkMonsterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MonsterIndexController extends AbstractController
{
    public function __construct(
        private ShadowdarkMonsterRepository $shadowdarkMonsterRepository,
    ) {
    }

    #[Route('/monster', name: 'monster_compendium.index')]
    public function __invoke(): Response
    {
        $monsters = $this->shadowdarkMonsterRepository->getMatchingByPhrase('sneaky ambusher');

        return $this->render('monster_compendium/index.html.twig', [
            'monsters' => $monsters,
        ]);
    }
}
