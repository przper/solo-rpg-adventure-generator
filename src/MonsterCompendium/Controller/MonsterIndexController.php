<?php

namespace App\MonsterCompendium\Controller;

use App\MonsterCompendium\Entity\ShadowdarkMonster;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MonsterIndexController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    #[Route('/monster', name: 'monster_compendium.index')]
    public function __invoke(): Response
    {
        $monsters = $this
            ->entityManager
            ->getRepository(ShadowdarkMonster::class)
            ->getMatchingByPhrase('Inhabits old ruins');

        return $this->render('monster_compendium/index.html.twig', [
            'monsters' => $monsters,
        ]);
    }
}
