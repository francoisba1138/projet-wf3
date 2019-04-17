<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BuyerController
 * @package App\Controller
 *
 * @Route("/membre")
 *
 */
class BuyerController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(User::class);

        $buyers =$repository->findBy(
            [
                'role' => 'ROLE_BUYER'
            ]
        );



        return $this->render('buyer/index.html.twig',
            [
                'buyers' => $buyers

            ]

        );
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"})
     */
     public function detail(User $buyer)
    {
        $role= $buyer->getRole();

        if ($role=='ROLE_BUYER') {


        return $this->render('buyer/profile.html.twig',
            [
                'buyer' => $buyer

            ]
        );
    } else {

    return $this->redirectToRoute('app_index_index');
    }



    }

    public function edit()
    {

    }


}
