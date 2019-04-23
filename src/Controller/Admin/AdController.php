<?php


namespace App\Controller\Admin;

use App\Form\AdadminType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Ad;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdController
 * @package App\Controller\Admin
 *
 * @Route("/admin/annonce")
 */
class AdController extends AbstractController
{

    /**
     * @Route("/")
     */
    public function index()
    {


        $repository = $this->getDoctrine()->getRepository(Ad::class);


        $ads =$repository->findAll();


        return $this->render('admin/ad/index.html.twig',

            [
                'ads' => $ads

            ]
        );



    }

    /**
     * @Route("/{id}")
     */
    public function detail(Ad $ad, Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(AdadminType::class, $ad );
        $form->handleRequest($request);

        if( $form->isSubmitted()){


                if( $form->isValid() ) {

         $em->persist($ad);
                    $em->flush();
                    $this->addFlash('success', "L'annonce est enregistré");
                    return $this->redirectToRoute('app_admin_ad_index');

                }else {
                    $this->addFlash('error', 'Le formulaire contient des erreurs');
                }
            }


        return $this->render('admin/ad/detail.html.twig',

            [
                'form' => $form->createView(),
                'ad' => $ad


            ]

            );



    }

    /**
     * @Route("/suppression/{id}")
     */
    public function delete(Ad $ad)
    { $em = $this->getDoctrine()->getManager();



        $em->remove($ad);
        $em->flush();
        $this->addFlash('success', "L'annonce est supprimée");
        return $this->redirectToRoute('app_admin_ad_index');


    }




    /**
     * @Route("/ajax/content/{id}")
     */
    public function ajaxContent(Request $request, Ad $ad)
    {

        if( $request->isXmlHttpRequest()) {

            return $this->render(
                'admin/ad/ajax_content.html.twig',
                ['ad' => $ad]
            );


        } else {

            throw new NotFoundHttpException();

        }

    }







}