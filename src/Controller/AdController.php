<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
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
    public function add(Ad $ad, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);

        if($form->isSubmitted()){
            if($form->isValid()){
                $em->persist($ad);
                    $em->flush();
                    $this->addFlash('success', "L'annonce est enregistré");
                    return $this->redirectToRoute('app_ad_index');
            }else{
                $this->addFlash('error', 'Le formulaire contient des erreurs');
            }
        }

        return $this->render(
            'ad/add.html.twig',
            [
                'form' => $form->createView(),
                'ad' => $ad
            ]
        );
    }



    /**
     * @Route("/detail")
     */
    public function detail()
    {
        return $this->render(
            'ad/detail.html.twig'
        );
    }
}