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
        $repository = $this->getDoctrine()->getRepository(Ad::class);
        $ad = $repository->findBy([],['date' => 'DESC']);

        return $this->render(
            'ad/index.html.twig',
            [
                'ad' => $ad
            ]
        );
    }
}
