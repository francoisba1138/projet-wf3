<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class CollectionController extends AbstractController
{
    /**
     * @Route("/collection")
     */
    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(User::class);

        $buyers = $repository->findBy(
            [
                'role' => 'ROLE_BUYER',

            ]
        );


        return $this->render('collection/index.html.twig',

            [
                'buyers'=> $buyers

            ]);
    }
}
