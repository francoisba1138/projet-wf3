<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class SellerController
 * @package App\Controller
 *
 * @Route("/vendeur")
 */
class SellerController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index()
    {

        $repository = $this->getDoctrine()->getRepository(User::class);

        $sellers =$repository->findBy(
            [
                'role' => 'ROLE_SELLER'
            ]

        );


        return $this->render('seller/index.html.twig',
            [
            'sellers' => $sellers
            ]
        );


    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"})
     */
    public function detail(User $seller)
    {

        $role= $seller->getRole();

        if ($role=='ROLE_SELLER') {


            return $this->render('seller/profile.html.twig',
                [
                    'seller' => $seller
                ]
            );

        } else {

            return $this->redirectToRoute('app_index_index');
        }

    }
}
