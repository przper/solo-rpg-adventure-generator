<?php

namespace App\MonsterCompendium\Controller;

use App\MonsterCompendium\Query\MonsterQuery;
use App\MonsterCompendium\Query\MonsterQueryParams;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

class MonsterIndexController extends AbstractController
{
    public function __construct(
        private MonsterQuery $monsterQuery,
    ) {
    }

    #[Route('/monster', name: 'monster_compendium.index', methods: ['GET'])]
    public function __invoke(
        #[MapQueryString] MonsterQueryParams $params
    ): Response {
        $monsters = call_user_func($this->monsterQuery, $params);

        return $this->render('monster_compendium/index.html.twig', [
            'monsters' => $monsters,
        ]);
    }
}
