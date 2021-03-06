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
     * @Route("/public_ad")
     */
    public function publicAds()
    {
        $repository = $this->getDoctrine()->getRepository(Ad::class);
        $ads = $repository->findAll();

        return $this->render(
            'ad/public_ads.html.twig',
            [
                'ads' => $ads
            ]
        );
    }



    /**
     * @Route("/mes_annonces")
     */
    public function index()
    {
        if($this->getUser() != null)
        {
            if ($this->getUser()->getRole() == 'ROLE_SELLER') {

                $repository = $this->getDoctrine()->getRepository(Ad::class);
                $ads = $repository->findBy(['seller' => $this->getUser()], ['date' => 'DESC']);

            } elseif ($this->getUser()->getRole() == 'ROLE_BUYER') {
                $repository = $this->getDoctrine()->getRepository(Ad::class);
                $ads = $repository->findBy(['buyer' => $this->getUser()], ['date' => 'DESC']);

            }

            return $this->render(
                'ad/index.html.twig',
                [
                    'ads' => $ads
                ]
            );
        } else {
        return $this->redirectToRoute('app_index_index');
        }
    }


    /**
     * @Route("/ajout")
     */
    public function add(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $ad = new Ad();
        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);

        if($form->isSubmitted()){
            if($form->isValid()){
                $ad
                    ->setDate(new \DateTime())
                    ->setSeller($this->getUser());

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
     * @Route("/{id}", requirements={"id": "\d+"})
     */
    public function detail(Ad $ad)
    {

        $date=$ad->getDate();
        $price=$ad->getPrice();
        $cond=$ad->getCond();
        $status=$ad->getStatus();
        $title=$ad->getTitle();
        $content=$ad->getContent();
        $game=$ad->getGame();
        $cover=$game->getCover();

        return $this->render(
            'ad/detail.html.twig',
            [
                'ad' => $ad,
                'date' => $date,
                'price' => $price,
                'cond' => $cond,
                'status' => $status,
                'title' => $title,
                'content' => $content,
                'game' => $game,
                'cover' => $cover
            ]
        );
    }
}
