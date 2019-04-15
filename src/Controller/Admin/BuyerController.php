<?php


namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BuyerController
 * @package App\Controller\Admin
 *
 * @Route("/admin/membre")
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

        return $this->render('admin/buyer/index.html.twig',

            [
                'buyers' => $buyers

            ]

            );


    }

    /**
     * @Route("/{id}")
     */
    public function detail(User $buyer, Request $request)
    {
        $role= $buyer->getRole();

        if ($role=='ROLE_BUYER') {


            $form = $this->createForm(UserType::class, $buyer );
            $form->handleRequest($request);

            if( $form->isSubmitted()){
                if( $form->isValid() ) {
                    $em->persist($buyer);
                    $em->flush();

                }

            }




            return $this->render('admin/buyer/detail.html.twig',
                [
                    'buyer' => $buyer,
                    'form' => $form->createView()

                ]
            );








        } else {

            return $this->redirectToRoute('app_index_index');
        }
    }






















    /**
     * @Route("/ajax/content/{id}")
     */
    public function ajaxContent(Request $request, User $buyer)
    {

        if( $request->isXmlHttpRequest()) {




            return $this->render(
                'admin/user/ajax_content.html.twig',
                ['buyer' => $buyer]
            );


        } else {

            throw new NotFoundHttpException();

        }

    }


}