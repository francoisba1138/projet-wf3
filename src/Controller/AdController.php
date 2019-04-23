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
    public function index(Request $request)
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
}
