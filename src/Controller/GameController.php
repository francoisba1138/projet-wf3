<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Game;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class GameController
 * @package App\Controller
 *
 * @Route("/jeu")
 */
class GameController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(Game::class);

        $games = $repository->findAll();

        return $this->render('game/index.html.twig', [
            'games' => $games
        ]);
    }
}
