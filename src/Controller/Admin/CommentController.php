<?php


namespace App\Controller\Admin;

use App\Entity\Comment;

use App\Form\CommentadminType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CommentController
 * @package App\Controller\Admin
 *
 * @Route("/admin/commentaire")
 */
class CommentController extends AbstractController
{

    /**
     * @Route("/")
     */
    public function index()
    {

        $repository = $this->getDoctrine()->getRepository(Comment::class);


        $comments = $repository->findAll();

        return $this->render('admin/comment/index.html.twig',

            [
                'comments' => $comments

            ]
        );

    }


    /**
     * @Route("/{id}")
     */
    public function detail(Comment $comment, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(CommentadminType::class, $comment);
        $form->handleRequest($request);

        if( $form->isSubmitted() ) {
            if( $form->isValid() ) {


                $em->persist($comment);
                $em->flush();
                $this->addFlash('success', "Le commentaire est enregistré");
                return $this->redirectToRoute('app_admin_comment_index');

            } else {
                $this->addFlash('error', 'Le formulaire contient des erreurs');

            }
        }


        return $this->render('admin/comment/detail.html.twig',


            [
                'form' => $form->createView(),
                'comment' => $comment


            ]

        );


    }

    /**
     * @Route("/suppression/{id}")
     */
    public function delete(Comment $comment)
    {
        $em = $this->getDoctrine()->getManager();


        $em->remove($comment);
        $em->flush();
        $this->addFlash('success', "Le commentaire est supprimé");
        return $this->redirectToRoute('app_admin_comment_index');


    }

    /**
     * @Route("/ajax/content/{id}")
     */
    public function ajaxContent(Request $request, Comment $comment)
    {

        if( $request->isXmlHttpRequest() ) {

            return $this->render(
                'admin/comment/ajax_content.html.twig',
                ['comment' => $comment]
            );


        } else {

            throw new NotFoundHttpException();

        }

    }
}