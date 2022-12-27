<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlayController extends AbstractController
{
    #[Route('/play', name: 'app_play')]
    public function index(): Response
    {
        $rowsCount = 15;
        $columnsCount = 15;

        $map = array_pad([], $rowsCount, array_pad([], $columnsCount, 0));

        $map[3][5] = 1;
        $map[rand(0, $rowsCount - 1)][rand(0, $columnsCount-1)] = 1;

        // dump($map);

        return $this->render('play/index.html.twig', [
            'rows_count' => $rowsCount,
            'columns_count' => $columnsCount,
            'map' => $map,
        ]);
    }
}
