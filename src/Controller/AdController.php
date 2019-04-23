<?php

namespace App\Controller;

use App\Entity\Ad;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdController
 * @package App\Controller
 *
 * @Route("/annonce")
 */
class AdController extends AbstractController
{
    /**
     * @Route("/{id}", requirements={"id": "\d+"})
     */
    public function index()
    {
        if ($this->getUser()->getRole()=='ROLE_SELLER'){

            dump($this->getUser()->getRole());

            $repository = $this->getDoctrine()->getRepository(Ad::class);
            $ads = $repository->findBy(['seller' => $this->getUser()],['date' => 'DESC']);
        }else{
            $repository = $this->getDoctrine()->getRepository(Ad::class);
            $ads = $repository->findBy(['buyer' => $this->getUser()],['date' => 'DESC']);
        }


        return $this->render(
            'ad/index.html.twig',
            [
                'ads' => $ads
            ]
        );
    }

    /**
     * @Route("/ajout")
     */
    public function detail()
    {
        return $this->render(
            'ad/detail.html.twig'
        );
    }
}
